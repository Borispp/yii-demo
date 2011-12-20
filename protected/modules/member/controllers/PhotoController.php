<?php
class PhotoController extends YsaMemberController
{
	public function actionView($photoId)
	{
		$entry = EventPhoto::model()->findByPk($photoId);
		
		if (!$entry) {
			$this->redirect(array('event/'));
		}
		
		$this->render('view', array(
			'entry' => $entry,
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
				$photo->delete();
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
}