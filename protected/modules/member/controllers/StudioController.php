<?php
class StudioController extends YsaMemberController
{
	public function beforeRender($view) {
		parent::beforeRender($view);
		
		$this->loadPlupload();
		
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/studiopage.js', CClientScript::POS_HEAD);
	
		return true;
	}
	
    public function actionIndex()
    {
		$entry = $this->member()->studio;

		$this->crumb('Studio');
		$this->setMemberPageTitle('Studio Information');
		
		$this->render('index', array(
			'entry'		=> $entry,
		));
    }
	
	public function actionDeleteSpecials()
	{
		$this->member()->studio->deleteSpecials();
		
		if (Yii::app()->request->isAjaxRequest) {
			$this->sendJsonSuccess(array(
				'html' => $this->renderPartial('_specialsUpload', array(
					'entry' => $this->member()->studio,
				), true)
			));
		} else {
			$this->redirect(array('studio/'));
		}
	}
	
	public function actionUploadSpecials()
	{
		if (isset($_POST['name'])) {
			$file = CUploadedFile::getInstanceByName('file');

			if (null === $file) {
				$this->sendJsonError(array(
					'msg' => 'No files uploaded. Please reload the page and try again.',
				));
			}

			$entry = $this->member()->studio;
			$entry->saveSpecials($file);

			$this->sendJsonSuccess(array(
				'html' => $this->renderPartial('_specialsPhoto', array(
					'entry' => $entry,
				), true)
			));
		} else {
			$this->sendJsonError(array(
				'msg' => 'No files uploaded. Please reload the page and try again.',
			));
		}
	}
	
	public function actionSaveGeneralInfo()
	{
		$entry = $this->member()->studio;
		
		if (isset($_POST['Studio'])) {
			$entry->attributes = $_POST['Studio'];
			if ($entry->validate()) {
				$entry->save();
				$msg = 'General information has been successfully saved.';
				if (Yii::app()->request->isAjaxRequest) {
					$this->sendJsonSuccess(array(
						'msg' => $msg,
					));
				} else {
					$this->setSuccess($msg);
					$this->redirect(array('studio/'));
				}
			} else {
				$msg = 'Something went wrong. Please reload the page and try again.';
				if (Yii::app()->request->isAjaxRequest) {
					$this->sendJsonError(array(
						'msg' => $msg,
					));
				} else {
					$this->setError($msg);
					$this->redirect(array('studio/'));
				}
			}
		}
		$this->redirect(array('studio/'));
	}
}