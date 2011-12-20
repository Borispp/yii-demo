<?php
class PersonController extends YsaMemberController
{
    public function actionAdd()
    {
		$entry = new StudioPerson();
		
		if (isset($_POST['StudioPerson'])) {
			
			$entry->attributes = $_POST['StudioPerson'];
			
			$entry->photo = CUploadedFile::getInstance($entry, 'photo');
			
			$entry->studio_id = $this->member()->studio()->id;
			
			$entry->setNextRank();
			
			if ($entry->validate()) {
				$entry->uploadPhoto();
				$entry->save();
				
				$this->redirect('studio/');
			}
		}
		
		$this->render('add', array(
			'entry' => $entry,
		));
    }
}