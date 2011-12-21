<?php
class PortfolioPhotoController extends YsaMemberController
{
	public function actionView($photoId)
	{
		$entry = PortfolioPhoto::model()->findByPk($photoId);
		
		if (!$entry) {
			$this->redirect(array('portfolio/'));
		}
		
		$this->render('view', array(
			'entry'			=> $entry,
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
			$photo = PortfolioPhoto::model()->findByPk($id);
			if ($photo) {
				$album = $photo->album();
				$photo->delete();
			}
        }
		
        if (Yii::app()->getRequest()->isAjaxRequest) {
            $this->sendJsonSuccess();
        } else {
			if (isset($album)) {
				$this->redirect(array('portfolioAlbum/view/' . $album->id));
			} else {
				$this->redirect(array('portfolio/'));
			}
        }
	}
	
	public function actionSort($album = 0)
	{
		if (Yii::app()->getRequest()->isAjaxRequest) {
			if (isset($_POST['album-photo'])) {
				foreach ($_POST['album-photo'] as $k => $id) {
					$entry = PortfolioPhoto::model()->findByPk($id);
					if ($entry) {
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
		$album = PortfolioAlbum::model()->findByPk($album);
		
		if (Yii::app()->getRequest()->isPostRequest && isset($_FILES['file']) && $album) {
			
			$uploaded = CUploadedFile::getInstanceByName('file');
			
			$photo = new PortfolioPhoto();
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
	
	public function filters()
	{
		return array(
			'accessControl -upload', // perform access control but for uploadAction
		);
	}
}