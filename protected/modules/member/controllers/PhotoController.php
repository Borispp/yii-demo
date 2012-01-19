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
			$this->redirect(array('event/'));
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
		
		$this->setMemberPageTitle('Photo #' . $entry->id);
		
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
		
		if ( !Yii::app()->getRequest()->getIsPostRequest() or !isset($_POST['EventPhotoComment']) )
			$this->redirect( array('photo/view/'.$entry->id) );
	
		// Control access rights
		if ( !$member->hasFacebook() or !$entry->canShareComments() )
			$this->redirect( array('photo/view/'.$entry->id) );
		
		$entryComment = new EventPhotoComment();
		$entryComment->attributes = $_POST['EventPhotoComment'];
		$entryComment->photo_id = $entry->id;

		if ($entryComment->validate()) 
		{
			$entryComment->save();
			$entryComment->appendToUser($member);
			
			if ( !empty($_POST['EventPhotoComment']['forward2facebook']) )
			{
				$authIdentity = Yii::app()->eauth->getIdentity( 'facebook', array('scope' => 'email,publish_stream'));
				if ($authIdentity->authenticate())
				{
					try
					{
						$data = array(
							'from' => Yii::app()->params['paramName']['facebook_app_id'],
							'message' => $entryComment->comment,
							'link' => $entry->shareUrl(),
							'picture' => $entry->fullUrl(),
						);
						$authIdentity->makeSignedRequest( 'https://graph.facebook.com/me/feed', array('data' => $data) );
					}
					catch( EAuthException $e )
					{
						switch ($e->getCode())
						{
							// access forbidden
							case 403: 
								$this->setError( 'No enought access rights to post on your Facebook wall. Please, <a href="/logout/">log in</a> again via Facebook and provide necessary access rights' ); 
								break;
							// token expired
							case 400:
							default:
								$this->setError( 'Unknown error while posting on your Facebook wall. You must <a href="/logout/">log out</a> and log in again with Facebook' );
						}
					}
				}
			}
			
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
					'msg' => 'Upload could not be completed. Please try again.',
				));
			}
			
		} else {
			$this->sendJsonError(array(
				'msg' => 'Something went wrong. Please try to reload the page and try again',
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
				'msg' => 'Something went wrong. Please try to reload the page and try again',
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
	
	
}