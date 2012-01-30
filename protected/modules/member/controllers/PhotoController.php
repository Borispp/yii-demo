<?php
class PhotoController extends YsaMemberController
{
	/**
	 * Ensure that given PhotoID is valid and has right owner
	 *
	 * @param integer $photoId
	 * @return EventPhoto
	 */
	protected function _ensureValidPhotoId( $photoId )
	{
		$entry = EventPhoto::model()->findByPk($photoId);
		
		if (!$entry || !$entry->album->event->isOwner()) {
			if (Yii::app()->request->isAjaxRequest) {
				$this->sendJsonError(array(
					'msg' => Yii::t('error', 'standart_error'),
				));
			} else {
				$this->redirect(array('event/'));
			}
		}
		
		return $entry;
	}
	
	public function actionView($photoId)
	{
		$entry = $this->_ensureValidPhotoId( $photoId );
		$photoSizes = PhotoSize::model()->findActive();		
		$entryComment = new EventPhotoComment();
		$availability = new AlbumPhotoAvailability();
		
		if (isset($_POST['EventPhotoComment'])) {
			$entryComment->attributes = $_POST['EventPhotoComment'];
			$entryComment->photo_id = $entry->id;
			$entryComment->validate();
		}
		
		if (isset($_POST['AlbumPhotoAvailability']) && !$entry->album->event->isProofing()) {
			$availability->attributes = $_POST['AlbumPhotoAvailability'];
			if ($availability->validate()) {
				$entry->can_order = $availability->can_order;
				$entry->can_share = $availability->can_share;
				
				$entry->save();
			}
			$this->refresh();
		}
		
//		if (isset($_POST['PhotoSizes']) && count($_POST['PhotoSizes']) && is_array($_POST['PhotoSizes'])) {
//			$entry->setSizes($_POST['PhotoSizes']);
//			$this->refresh();
//		}
		
		
		$this->crumb('Events', array('event/'))
			 ->crumb($entry->album->event->name, array('event/view/' . $entry->album->event->id))
			 ->crumb($entry->album->name, array('album/view/' . $entry->album->id))
			 ->crumb('Photo #' . $entry->id);
		
		$this->setMemberPageTitle($entry->title());
		
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/photopage.js', CClientScript::POS_HEAD);
		
		$this->render('view', array(
			'entry'			=> $entry,
			'entryComment'	=> $entryComment,
			'photoSizes'	=> $photoSizes,
			'availability'	=> $availability,
			'member'		=> $this->member()
		));
	}
	
	/**
	 * Save comment. This is a meta action in sense that it has no own view
	 * 
	 * @param integer $photoId 
	 */
	public function actionComment( $photoId = 0 )
	{
		$entry = $this->_ensureValidPhotoId( $photoId );
		$member = $this->member();
		
		if ( !Yii::app()->request->isPostRequest or !isset($_POST['EventPhotoComment']) )
			$this->redirect( array('photo/view/'.$entry->id) );
		
		// Control access rights
		if ( !$entry->canBeCommented() )
			$this->redirect( array('photo/view/'.$entry->id) );
		
		$entryComment = new EventPhotoComment();
		$entryComment->attributes = $_POST['EventPhotoComment'];
		$entryComment->photo_id = $entry->id;

		if ($entryComment->validate()) 
		{
			$entryComment->save();
			$entryComment->appendToUser($member);
			$this->redirect( array('photo/view/'.$entry->id) );
		}
		
		$this->forward( 'view' );
	}
	
	public function actionDelete($photoId = 0)
	{
		$ids = array();
		if (isset($_POST['ids']) && count($_POST['ids'])) {
			$ids = $_POST['ids'];
		} elseif ($photoId) {
			$ids = array(intval($photoId));
		}
		
		foreach ($ids as $id) {
			$photo = EventPhoto::model()->findByPk($id);
			if ($photo) {
				$album = $photo->album;
				if ($photo->isOwner()) {
					$photo->delete();
				}
			}
		}
		
		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			if (isset($album)) {
				$this->redirect(array('album/view/' . $album->id));
			} else {
				$this->redirect(array('event/'));
			}
		}
	}
	
	public function actionSort()
	{
		if (Yii::app()->getRequest()->isAjaxRequest) {
			if (isset($_POST['album-photo'])) {
				foreach ($_POST['album-photo'] as $k => $id) {
					$entry = EventPhoto::model()->findByPk($id);
					if ($entry && $entry->isOwner()) {
						$entry->rank = $k + 1;
						$entry->save();
					}
				}				
			}

			$this->sendJsonSuccess();
		} else {
			$this->redirect(Yii::app()->homeUrl);
		}
	}
	
	public function actionUpload($album = 0)
	{
		$album = EventAlbum::model()->findByPk($album);
		
		if (Yii::app()->getRequest()->isPostRequest && isset($_FILES['file']) && $album && $album->isOwner()) {
			
			$uploaded = CUploadedFile::getInstanceByName('file');
			
			$photo = new EventPhoto();
			$photo->album_id = $album->id;
			
			if ($photo->upload($uploaded)) {
				$this->sendJsonSuccess(array(
					'html' => $this->renderPartial('_listphoto', array(
						'entry' => $photo,
					), true)
				));
				
			} else {
				$this->sendJsonError(array(
					'msg' => Yii::t('error', 'upload_couldnt_be_completed'),
				));
			}
			
		} else {
			$this->sendJsonError(array(
				'msg' => Yii::t('error', 'standart_error'),
			));
		}
	}
	
	
	/**
	 * Import all photos to action
	 * @param integer $album 
	 */
	public function actionSmugmugImport($album = 0)
	{
		$album = EventAlbum::model()->findByPk($album);
		
		if (Yii::app()->getRequest()->isPostRequest && isset($_POST['smugmug']) && $album && $album->isOwner()) {
			list ($smugmugAlbumId, $smugmugAlbumKey) = explode('|', $_POST['smugmug']);
			
			try {
				$this->member()->smugmugSetAccessToken();

				$images = $this->member()->smugmug()->images_get('AlbumID=' . $smugmugAlbumId, 'AlbumKey=' . $smugmugAlbumKey);

				$w = Yii::app()->params['member_area']['photo']['full']['width'];
				$h = Yii::app()->params['member_area']['photo']['full']['height'];			

				foreach ($images['Images'] as $image) {
					$image = $this->member()->smugmug()->images_getInfo('ImageID=' . $image['id'], 'ImageKey=' . $image['Key'], 'CustomSize=' . $w . 'x' . $h);
					$image['exif'] = $this->member()->smugmug()->images_getEXIF('ImageID=' . $image['id'], 'ImageKey=' . $image['Key']);
					
					$photo = new EventPhoto();
					$photo->album_id = $album->id;
					$photo->import($image, 'smugmug');
				}
			
				$this->sendJsonSuccess();
				
			} catch (Exception $e) {
				$this->sendJsonError(array(
					'msg' => $e->getMessage(),
				));
			}
		} else {
			$this->sendJsonError(array(
				'msg' => Yii::t('error', 'standart_error'),
			));
		}
	}
	
	
	/**
	 * Import a single photo to album
	 */
	public function actionSmugmugImportPhoto()
	{
		if (Yii::app()->getRequest()->isAjaxRequest && isset($_POST['smugmug']) && isset($_POST['album_id'])) {
			
			try {
				$this->member()->smugmugSetAccessToken();
				$album = EventAlbum::model()->findByPk($_POST['album_id']);
				
				if (!$album || !$album->isOwner()) {
					throw new Exception('No Album ID provided.');
				}
				
				list ($smugmugPhotoId, $smugmugPhotoKey) = explode('|', $_POST['smugmug']);
				
				$w = Yii::app()->params['member_area']['photo']['full']['width'];
				$h = Yii::app()->params['member_area']['photo']['full']['height'];	
				
				$image = $this->member()->smugmug()->images_getInfo('ImageID=' . $smugmugPhotoId, 'ImageKey=' . $smugmugPhotoKey, 'CustomSize=' . $w . 'x' . $h);
				$image['exif'] = $this->member()->smugmug()->images_getEXIF('ImageID=' . $smugmugPhotoId, 'ImageKey=' . $smugmugPhotoKey);
				
				$entry = new EventPhoto();
				$entry->album_id = $album->id;
				$entry->import($image, 'smugmug');
				
				$this->sendJsonSuccess(array(
					'html' => $this->renderPartial('_listphoto', array('entry' => $entry), true),
				));
				
			} catch (Exception $e) {
				$this->sendJsonError(array(
					'msg' => $e->getMessage(),
				));
			}
		}
		Yii::app()->end();
	}
	
	public function actionSaveSizes($photoId)
	{
		if (isset($_POST['PhotoSizes']) && count($_POST['PhotoSizes']) && is_array($_POST['PhotoSizes'])) {
			$entry = $this->_ensureValidPhotoId( $photoId );
			
			// set order sizes
			$entry->setSizes($_POST['PhotoSizes']);
			
			if (Yii::app()->request->isAjaxRequest) {
				$this->sendJsonSuccess();
			} else {
				$this->redirect(array('photo/view/' . $entry->id));
			}
		} else {
			$this->redirect(array('event/'));
		}
	}
	
	public function actionSaveAvailability($photoId)
	{
		if (isset($_POST['AlbumPhotoAvailability'])) {
			$entry = $this->_ensureValidPhotoId( $photoId );
			$availability = new AlbumPhotoAvailability();
			$availability->attributes = $_POST['AlbumPhotoAvailability'];
			
			if ($availability->validate()) {
				$entry->can_order = $availability->can_order;
				$entry->can_share = $availability->can_share;
				$entry->save();
			}
			if (Yii::app()->request->isAjaxRequest) {
				$this->sendJsonSuccess();
			} else {
				$this->redirect(array('album/view/' . $entry->id));
			}
		}
	}
	
	public function actionToggle($photoId = 0)
	{
		if (Yii::app()->getRequest()->isAjaxRequest) {
			$entry = EventPhoto::model()->findByPk($photoId);
			if ($entry && $entry->isOwner()) {
				if (isset($_POST['state']) && in_array($_POST['state'], array_keys(EventPhoto::model()->getStates()))) {
					$entry->state = intval($_POST['state']);
					$entry->save();
					$this->sendJsonSuccess();
				} else {
					$this->sendJsonError(array(
						'msg' => Yii::t('error', 'standart_error'),
					));
				}
			}
			
		} else {
			$this->redirect(Yii::app()->homeUrl);
		}
	}
	
	public function actionRedact($photoId, $act, $v = '')
	{
		$photo = $this->_ensureValidPhotoId($photoId);
		
		switch ($act) {
			case 'rotate':
				$success = $photo->rotate($v == 'left' ? 90 : -90);
				break;
			case 'flip':
				$success = $photo->flip($v == 'horiz' ? YsaImage::HORIZONTAL : YsaImage::VERTICAL);
				break;
			default:
				break;
		}
		if (Yii::app()->request->isAjaxRequest) {
			if ($success) {
				$this->sendJsonSuccess(array(
					'url' => $photo->url() . '?' . microtime(),
				));
			} else {
				$this->setError(Yii::t('error', 'standart_error'));
			}
		} else {
			if ($success) {
				$this->setSuccess(Yii::t('save', 'photo_edited'));
			} else {
				$this->setError(Yii::t('error', 'standart_error'));
			}
		}
		$this->redirect(array('photo/view/' . $photo->id));
	}
	
	public function actionRestore($photoId)
	{
		$photo = $this->_ensureValidPhotoId($photoId);
		
		$success = $photo->restore();
		
		if (Yii::app()->request->isAjaxRequest) {
			if ($success) {
				$this->sendJsonSuccess(array(
					'url' => $photo->url() . '?' . microtime(),
				));
			} else {
				$this->setError(Yii::t('error', 'standart_error'));
			}
		} else {
			if ($success) {
				$this->setSuccess(Yii::t('save', 'photo_edited'));
			} else {
				$this->setError(Yii::t('error', 'standart_error'));
			}
		}
		
		$this->redirect(array('photo/view/' . $photo->id));
	}
}