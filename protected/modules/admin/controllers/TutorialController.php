<?php
class TutorialController extends YsaAdminController
{
	public function actionIndex()
	{
		$tutorialSearch = new YsaTutorialSearchForm();
		$tutorialSearch->setAttributes(!empty($_GET['YsaTutorialSearchForm']) ? $_GET['YsaTutorialSearchForm'] : array(),false);
		$criteria = $tutorialSearch->searchCriteria();
		
		$pagination = new CPagination(Tutorial::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = Tutorial::model()->findAll($criteria);

		$this->setContentTitle('Tutorial Management');
		$this->setContentDescription('view all tutorials.');

		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/adm/js/search-form.js', CClientScript::POS_HEAD);
		$this->render('index',array(
			'entries'   => $entries,
			'pagination'=> $pagination,
			'tutorialSearch' => $tutorialSearch,
		));
	}
	
	public function actionAdd()
	{
		$entry = new Tutorial();

		if(isset($_POST['Tutorial'])) {
			$entry->attributes=$_POST['Tutorial'];
			if (!$entry->slug) {
				$entry->generateSlugFromTitle();
			}
			
			if ($entry->validate()) {
				$entry->save();
				
				$entry->preview = CUploadedFile::getInstance($entry, 'preview');
				$entry->uploadPreview(true);
				
				if (isset($_FILES['file']) && is_array($_FILES['file'])) {
					foreach ($_FILES['file']['name'] as $k => $name) {
						if ($name && !$_FILES['file']['error'][$k]) {
							$entry->uploadFile(
								isset($_POST['file'][$k]) ? $_POST['file'][$k] : $name,
								new CUploadedFile(
									$_FILES['file']['name'][$k],
									$_FILES['file']['tmp_name'][$k],
									$_FILES['file']['type'][$k],
									$_FILES['file']['size'][$k],
									$_FILES['file']['error'][$k]
								)
							);
						}
					}
				}
				
				$this->setSuccessFlash("New entry successfully added. " . YsaHtml::link('Back to listing.', array('index')));
				$this->redirect(array('edit', 'id'=>$entry->id));
			}
		}

		$this->setContentTitle('Add New Tutorial');
		$this->setContentDescription('Fill the form to add new tutorial.');

		$this->render('add',array(
			'entry'     => $entry,
		));
	}
	
	public function actionEdit($id)
	{
		$id = (int) $id;

		$entry = Tutorial::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}

		if(Yii::app()->request->isPostRequest && isset($_POST['Tutorial'])) {
			$entry->attributes=$_POST['Tutorial'];
			
			if (!$entry->slug) {
				$entry->generateSlugFromTitle();
			}
			
			if ($entry->validate()) {
				$entry->save();
				
				$preview = CUploadedFile::getInstance($entry, 'preview');
				// delete old preview
				if ($preview) {
					$entry->deletePreview();
				}
				$entry->preview = $preview;	
				$entry->uploadPreview(true);
				
				if (isset($_FILES['file']) && is_array($_FILES['file'])) {
					foreach ($_FILES['file']['name'] as $k => $name) {
						if ($name && !$_FILES['file']['error'][$k]) {
							$entry->uploadFile(
								isset($_POST['file'][$k]) ? $_POST['file'][$k] : $name,
								new CUploadedFile(
									$_FILES['file']['name'][$k],
									$_FILES['file']['tmp_name'][$k],
									$_FILES['file']['type'][$k],
									$_FILES['file']['size'][$k],
									$_FILES['file']['error'][$k]
								)
							);
						}
					}
				}
				
				if (isset($_POST['uploadedfile']) && is_array($_POST['uploadedfile'])) {
					foreach ($_POST['uploadedfile'] as $id => $name) {
						$file = TutorialFile::model()->findByPk($id);
						if ($file) {
							$file->name = $name;
							$file->save();
						}
					}
				}
				
				$this->setSuccessFlash("Entry successfully updated. " . YsaHtml::link('Back to listing.', array('index')));
				$this->refresh();
			}
		}
		
		$this->setContentTitle('Edit Tutorial');
		$this->setContentDescription('edit tutorial details.');

		
		$this->render('edit',array(
			'entry'     => $entry,
		));
	}
	
	public function actionDelete()
	{
		$ids = array();
		if (isset($_POST['ids']) && count($_POST['ids'])) {
			$ids = $_POST['ids'];
		} elseif (isset($_GET['id'])) {
			$ids = array(intval($_GET['id']));
		}

		foreach ($ids as $id) {
			Tutorial::model()->findByPk($id)->delete();
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionDeleteFile()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_POST['id'])) {
			$file = TutorialFile::model()->findByPk($_POST['id']);
			if ($file) {
				$file->delete();
			}
			$this->sendJsonSuccess();
		} else {
			$this->sendJsonError();
		}	
	}
	
	public function actionDeleteImage($id)
	{
		$file = Tutorial::model()->findByPk($id);
		if ($file) {
			$file->deletePreview(true);
			$this->redirect(array('tutorial/edit/id/' . $file->id));
		} else {
			$this->redirect(array('index'));
		}
		
	}
}