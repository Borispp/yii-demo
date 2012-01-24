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
		
		$entry = new Client();
		
		if (isset($_POST['Client']))
		{
			$entry->attributes = $_POST['Client'];
			
			$entry->user_id = $this->member()->id;
			
			$eventList = isset($_POST['Client']['eventList']) && is_array($_POST['Client']['eventList']) ? $_POST['Client']['eventList'] : array();
			
			if ($entry->validate()) {
				$entry->save();
				$entry->setEvents($eventList);
				$this->setSuccess('Client has been added successfully.');
				$this->redirect(array('client/'));
			} else {
				if (isset($_POST['Client']['eventList']) && is_array($_POST['Client']['eventList'])) {
					$entry->selectedEvents = $entry->prepareSelectedEvents($_POST['Client']['eventList']);
				}
			}
		}
		$this->setMemberPageTitle('New Client');
		
		$this->crumb('New Client');
		
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/clientedit.js', CClientScript::POS_HEAD);
		
		$this->render('add', array(
			'entry'	=> $entry,
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
		
		$entry = Client::model()->findByPk($clientId);
		if (!$entry || !$entry->isOwner()) {
			$this->redirect(array('client/'));
		}
		
		$this->crumb('View Client');
		$this->setMemberPageTitle('View Client');
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/clientpage.js', CClientScript::POS_HEAD);
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/notification_button.js', CClientScript::POS_HEAD);
		
		$this->render('view', array(
			'entry' => $entry,
		));
	}

	public function actionEdit($clientId)
	{
		
		$entry = Client::model()->findByPk($clientId);

		if (!$entry || !$entry->isOwner())
		{
			$this->redirect(array('client/'));
		}
		
		$entry->selectedEvents = $entry->selectedEvents();
		
		if (isset($_POST['Client']))
		{
			$entry->attributes = $_POST['Client'];
			
			$eventList = isset($_POST['Client']['eventList']) && is_array($_POST['Client']['eventList']) ? $_POST['Client']['eventList'] : array();
			
			if ($entry->validate()) {
				$entry->save();
				$entry->setEvents($eventList);
				$this->setSuccess('Client has been successfully modified.');
				$this->redirect(array('client/'));
			} else {
				if (isset($_POST['Client']['eventList']) && is_array($_POST['Client']['eventList'])) {
					$entry->selectedEvents = $entry->prepareSelectedEvents($_POST['Client']['eventList']);
				}
			}
		}

		$this->crumb('Edit Client');
		$this->setMemberPageTitle('Edit Client');
		
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/clientedit.js', CClientScript::POS_HEAD);

		$this->render('edit', array(
			'entry'		=> $entry,
			'events'    => $this->member()->event,
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
	
	public function actionToggle($clientId = 0)
	{
		if (Yii::app()->getRequest()->isAjaxRequest) {
			$entry = Client::model()->findByPk($clientId);
			if ($entry && $entry->isOwner()) {
				if (isset($_POST['state']) && in_array($_POST['state'], array_keys(Client::model()->getStates()))) {
					$entry->state = intval($_POST['state']);
					$entry->save();
					$this->sendJsonSuccess();
				} else {
					$this->sendJsonError(array(
						'msg' => 'Something went wrong. Please reload the page and try again',
					));
				}
			}
			
		} else {
			$this->redirect(Yii::app()->homeUrl);
		}
	}
}