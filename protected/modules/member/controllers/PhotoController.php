<?php
class PhotoController extends YsaMemberController
{
	public function actionView($photoId)
	{
		$entry = EventPhoto::model()->findByPk($photoId);
		
		if (!$entry || !$entry->album()->event()->isOwner()) {
			$this->redirect(array('event/'));
		}
		
		$photoSizes = PhotoSize::model()->findActive();
		
		$entryComment = new EventPhotoComment();
		
		$availability = new AlbumPhotoAvailability();
		
		if (isset($_POST['EventPhotoComment'])) {
			$entryComment->attributes = $_POST['EventPhotoComment'];
			
			$entryComment->user_id = $this->member()->id;
			$entryComment->photo_id = $entry->id;
			
			if ($entryComment->validate()) {
				$entryComment->save();
				$this->refresh();
			}
		}
		
		if (isset($_POST['AlbumPhotoAvailability']) && !$entry->album()->event()->isProofing()) {
			$availability->attributes = $_POST['AlbumPhotoAvailability'];
			if ($availability->validate()) {
				$entry->can_order = $availability->can_order;
				$entry->can_share = $availability->can_share;
				
				$entry->save();
			}
			$this->refresh();
		}
		
		if (isset($_POST['PhotoSizes']) && count($_POST['PhotoSizes']) && is_array($_POST['PhotoSizes'])) {
			$entry->setSizes($_POST['PhotoSizes']);
			$this->refresh();
		}
		
		
		$this->crumb('Events', array('event/'))
			 ->crumb($entry->album()->event()->name, array('event/view/' . $entry->album()->event()->id))
			 ->crumb($entry->album()->name, array('album/view/' . $entry->album()->id))
			 ->crumb('Photo #' . $entry->id);
		
		$this->setMemberPageTitle('Photo #' . $entry->id);
		
		$this->render('view', array(
			'entry'			=> $entry,
			'entryComment'	=> $entryComment,
			'photoSizes'	=> $photoSizes,
			'availability'	=> $availability,
		));
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
				$album = $photo->album();
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
	
	public function actionSort($albumId = 0)
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
}