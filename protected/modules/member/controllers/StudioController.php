<?php
class StudioController extends YsaMemberController
{
    public function actionIndex()
    {
		$entry = $this->member()->studio();
		
		$entryLink = new StudioLink();
		
		$specials = new SpecialsUploadForm();
		
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
			'specials'  => $specials,
		));
    }
}