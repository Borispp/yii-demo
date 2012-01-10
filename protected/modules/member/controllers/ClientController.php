<?php
class ClientController extends YsaMemberController
{
	public function init()
	{
		parent::init();
		$this->crumb('Clients', array('client/'));
	}
	public function actionAdd()
	{
		$this->crumb('New Client');
		$entry = new Client();
		if (isset($_POST['Client']))
		{
			$entry->attributes = $_POST['Client'];
			$entry->application_id = $this->member()->application->id;
			if ($entry->validate())
			{
				$entry->save();
				$this->redirect(array('client/'));
			}
		}
		$this->render('add', array(
				'entry' => $entry,
			));
	}

	public function actionIndex()
	{
		if (isset($_POST['Fields'])) {
			if (isset($_POST['SearchBarReset']) && $_POST['SearchBarReset']) {
				Client::model()->resetSearchFields();
			} else {
				Client::model()->setSearchFields($_POST['Fields']);
			}
			$this->redirect(array('client/'));
		}

		$criteria = Client::model()->searchCriteria();

		// load only current user's events
		$criteria->addSearchCondition('application_id', $this->member()->application->id);

		$pagination = new CPagination(Client::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = Client::model()->findAll($criteria);

		$this->setMemberPageTitle('Clients');

		$this->render('index',array(
			'entries'       => $entries,
			'pagination'    => $pagination,
			'searchOptions' => Client::model()->searchOptions(),
		));
	}

	public function actionView($clientId)
	{
		$this->crumb('View Client');
		$entry = Client::model()->findByPk($clientId);
		if (!$entry || !$entry->isOwner())
		{
			$this->redirect(array('client/'));
		}
		$this->setMemberPageTitle('View Client');
		$this->render('view', array(
				'entry' => $entry,
			));
	}

	public function actionEdit($clientId)
	{
		$this->crumb('Edit Client');
		$entry = Client::model()->findByPk($clientId);

		if (!$entry || !$entry->isOwner())
		{
			$this->redirect(array('client/'));
		}
		if (isset($_POST['Client']))
		{
			$entry->attributes = $_POST['Client'];
			if ($entry->validate())
			{
				$entry->save();
				$this->redirect(array('client/'));
			}
		}

		$this->setMemberPageTitle('Edit Client');

		$this->render('edit', array(
				'entry' => $entry,
			));
	}

	public function actionDelete($clientId = 0)
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
}