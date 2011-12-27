<?php
class StudioController extends YsaMemberController
{
    public function actionIndex()
    {
		$entry = $this->member()->studio;
		
		$entryLink = new StudioLink();
		
		$specials = new SpecialsUploadForm();
		
		$splash = new StudioSplashForm();
		$splash->text = $entry->splash;
		
		if (isset($_POST['Studio'])) {
			$entry->attributes = $_POST['Studio'];
			
			if ($entry->validate()) {
				$entry->save();
				$this->refresh();
			}
		}
		
		if (isset($_POST['StudioLink'])) {
			$entryLink->attributes = $_POST['StudioLink'];
			$entryLink->studio_id = $this->member()->studio->id;
			$entryLink->setNextRank();
			
			if ($entryLink->validate()) {
				$entryLink->save();
				$this->refresh();
			}
		}
		
		if (isset($_POST['SpecialsUploadForm'])) {
			$specials->specials = CUploadedFile::getInstance($specials, 'specials');
			
			if ($specials->validate()) {
				$entry->saveSpecials($specials->specials);
				$this->refresh();
			}	
		}
		
		if (isset($_POST['StudioSplashForm'])) {
//			$specials->specials = CUploadedFile::getInstance($specials, 'specials');
			
			$splash->attributes = $_POST['StudioSplashForm'];
			
			if ($splash->validate()) {
				
				$entry->splash = $splash->text;
				$entry->save();
				
				$this->refresh();
			}	
		}
		
		$this->crumb('Studio');
		
		$this->setMemberPageTitle('Studio Information');
		
		$this->render('index', array(
			'entry'		=> $entry,
			'entryLink' => $entryLink,
			'specials'  => $specials,
			'splash'	=> $splash,
		));
    }
	
	public function actionDeleteSpecials()
	{
		$this->member()->studio->deleteSpecials();
		
		if (Yii::app()->request->isAjaxRequest) {
			$specials = new SpecialsUploadForm();
			$this->sendJsonSuccess(array(
				'html' => $this->renderPartial('_specialsForm', array(
					'entry' => $specials,
				), true)
			));
		} else {
			$this->redirect(array('studio/'));
		}
	}
}