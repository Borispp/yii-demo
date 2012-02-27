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
}