<?php
class LinkController extends YsaMemberController
{
    public function actionAdd()
    {
		$entry = new StudioLink();
		
		if (isset($_POST['StudioLink'])) {
			
			$entry->attributes = $_POST['StudioLink'];
			$entry->studio_id = $this->member()->studio->id;
			
			$entry->setNextRank();
			
			if ($entry->validate()) {
				$entry->save();
				$this->redirect(array('studio/'));
			}
		}
		
		$this->setMemberPageTitle('Add Shooter');
		
		$this->render('add', array(
			'entry' => $entry,
		));
    }
	
	public function actionEdit($linkId)
	{
		$entry = StudioLink::model()->findByPk($linkId);
		
		if (!$entry || !$entry->isOwner()) {
			$this->redirect(array('studio/'));
		}
		
		if (isset($_POST['StudioLink'])) {
			$entry->attributes = $_POST['StudioLink'];
			if ($entry->validate()) {
				$entry->save();
				$this->redirect(array('studio/'));
			}
		}
		
		$this->setMemberPageTitle('Edit Link');
		
		$this->render('edit', array(
			'entry' => $entry,
		));
	}
	
	public function actionDelete($linkId = 0)
	{
        $ids = array();
        if (isset($_POST['ids']) && count($_POST['ids'])) {
            $ids = $_POST['ids'];
        } elseif ($linkId) {
            $ids = array(intval($linkId));
        }
		
        foreach ($ids as $id) {
			$entry = StudioLink::model()->findByPk($id);
			if ($entry && $entry->isOwner()) {
				$entry->delete();	
			}
        }
        
        if (Yii::app()->getRequest()->isAjaxRequest) {
            $this->sendJsonSuccess();
        } else {
			$this->redirect(array('studio/'));
        }
	}
	
	public function actionSort()
	{
		if (Yii::app()->getRequest()->isAjaxRequest) {
			if (isset($_POST['studio-link'])) {
				foreach ($_POST['studio-link'] as $k => $id) {
					$entry = StudioLink::model()->findByPk($id);
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
}