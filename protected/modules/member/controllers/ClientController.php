<?php
class ClientController extends YsaMemberController
{
	public function init()
	{
		parent::init();
		$this->crumb('Clients', array('client/'));
		$this->renderVar('events', $this->member()->event);
	}
	public function actionAdd()
	{
		$this->crumb('New Client');
		$entry = new Client();
		if (isset($_POST['Client']))
		{
			$entry->attributes = $_POST['Client'];
			$entry->user_id = $this->member()->id;
			if ($entry->validate())
			{
				$entry->save();
				$this->handleEvents($entry);
				$this->redirect(array('client/'));
			}
		}
		$this->setMemberPageTitle('New Client');
		$this->render('add', array(
				'entry' => $entry,
			));
	}

	protected function handleEvents(Client $obClient)
	{
		foreach($obClient->events as $obEvent)
		{
			$obClient->removePhotoEvent($obEvent);
		}
		if (!empty($_POST['events']))
		{
			foreach(explode(',', $_POST['events']) as $eventId)
			{
				$obEvent = Event::model()->findByPk($eventId);
				if (!$obEvent)
				{
					continue;
				}
				$obClient->addPhotoEvent($obEvent);
			}
		}
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
		$criteria->addSearchCondition('user_id', $this->member()->id);

		$pagination = new CPagination(Client::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = Client::model()->findAll($criteria);

		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/clientlist.js', CClientScript::POS_HEAD);

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
				$this->handleEvents($entry);
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
		} elseif ($clientId) {
			$ids = array(intval($clientId));
		}

		foreach ($ids as $id) {
			$entry = Client::model()->findByPk($id);
			if ($entry && $entry->isOwner()) {
				$entry->delete();
			}
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(array('client/'));
		}
	}
}