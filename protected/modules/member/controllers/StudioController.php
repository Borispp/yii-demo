<?php
class StudioController extends YsaMemberController
{
    public function actionIndex()
    {
		$entry = $this->member()->studio();
		
		$entryLink = new StudioLink();
		
		if (isset($_POST['Studio'])) {
			$entry->attributes = $_POST['Studio'];
			
			if ($entry->validate()) {
				$entry->save();
				$this->refresh();
			}
		}
		
		if (isset($_POST['StudioLink'])) {
			$entryLink->attributes = $_POST['StudioLink'];
			$entryLink->studio_id = $this->member()->studio()->id;
			$entryLink->setNextRank();
			
			if ($entryLink->validate()) {
				$entryLink->save();
				
				$this->refresh();
			}
		}
		
		$this->render('index', array(
			'entry'		=> $entry,
			'entryLink' => $entryLink,
		));
    }
	
	public function actionEditlink($id)
	{
		$entryLink = StudioLink::model()->findByPk($id);
		
		if (!$entryLink) {
			$this->redirect(array('studio/'));
		}
		
		if (isset($_POST['StudioLink'])) {
			$entryLink->attributes = $_POST['StudioLink'];
			if ($entryLink->validate()) {
				$entryLink->save();
				$this->redirect(array('studio/'));
			}
		}
		
		$this->render('editlink', array(
			'entryLink' => $entryLink,
		));
	}
}