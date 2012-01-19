<?php
class PersonController extends YsaMemberController
{
    public function actionAdd()
    {
		$entry = new StudioPerson();
		
		if (isset($_POST['StudioPerson'])) {
			
			$entry->attributes = $_POST['StudioPerson'];
			$entry->photo = CUploadedFile::getInstance($entry, 'photo');
			$entry->studio_id = $this->member()->studio->id;
			
			$entry->setNextRank();
			
			if ($entry->validate()) {
				$entry->uploadPhoto();
				$entry->save();
				
				$this->setSuccess('Shooter was successfully added.');
				
				$this->redirect(array('studio/'));
			}
		}
		
		
		if (Yii::app()->request->isAjaxRequest || isset($_GET['iframe'])) {
			$this->renderPartial('add', array(
				'entry' => $entry,
			));
			Yii::app()->end();
		}
		
		
		$this->setMemberPageTitle('Add Shooter');
		
		$this->crumb('Studio', array('studio/'))
			 ->crumb('Add Shooter');
		
		$this->render('add', array(
			'entry' => $entry,
		));
    }
	
	public function actionView($personId)
	{
		$entry = StudioPerson::model()->findByPk($personId);
		
		if (!$entry || !$entry->isOwner()) {
			$this->redirect(array('studio/'));
		}
		
		if (Yii::app()->request->isAjaxRequest) {
			$this->renderPartial('view', array(
				'entry' => $entry,
			));
			Yii::app()->end();
		}
		
		$this->setMemberPageTitle('View Shooter');
		
		$this->crumb('Studio', array('studio/'))
			 ->crumb('View Shooter');
		
		$this->render('view', array(
			'entry' => $entry,
		));
	}
	
	public function actionEdit($personId)
	{
		$entry = StudioPerson::model()->findByPk($personId);
		
		if (!$entry || !$entry->isOwner()) {
			$this->redirect(array('studio/'));
		}
		
		if (isset($_POST['StudioPerson'])) {
			
			$entry->attributes = $_POST['StudioPerson'];
			
			$uploaded = CUploadedFile::getInstance($entry, 'photo');
			if ($uploaded) {
				$entry->photo = CUploadedFile::getInstance($entry, 'photo');
			}

			if ($entry->validate()) {
				$entry->uploadPhoto();
				$entry->save();
				$this->redirect(array('studio/'));
			}
		}
		
		$this->setMemberPageTitle('Edit Shooter');
		
		$this->render('edit', array(
			'entry' => $entry,
		));
	}
	
	public function actionDelete($personId = 0)
	{
        $ids = array();
        if (isset($_POST['ids']) && count($_POST['ids'])) {
            $ids = $_POST['ids'];
        } elseif ($personId) {
            $ids = array(intval($personId));
        }
		
        foreach ($ids as $id) {
			$entry = StudioPerson::model()->findByPk($id);
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
	
	public function actionDeleteImage($personId)
	{
		$entry = StudioPerson::model()->findByPk($personId);
		
		if (!$entry || !$entry->isOwner()) {
			$this->redirect(array('studio/'));
		}
		
		$entry->removePhoto();
		
		$this->redirect(array('studio/person/' . $entry->id));
	}
	
	public function actionSort()
	{
		if (Yii::app()->getRequest()->isAjaxRequest) {
			if (isset($_POST['studio-person'])) {
				foreach ($_POST['studio-person'] as $k => $id) {
					$entry = StudioPerson::model()->findByPk($id);
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