<?php
class NotificationController extends YsaMemberController
{
	public function init()
	{
		parent::init();
		$this->crumb('Clients', array('client/'));
		$this->crumb('Notifications', array('notification/'));
	}

	public function actionNew()
	{
		$this->crumb('Send notification');
		$entry = new ApplicationNotification();
		if (isset($_POST['ApplicationNotification']))
		{
			$entry->attributes = $_POST['ApplicationNotification'];
			var_dump('ApplicationNotification');
			$entry->application_id = $this->member()->application->id;
			if ($entry->validate())
			{
				$entry->save();
				$this->redirect(array('notification/'));
			}
		}
		$this->setMemberPageTitle('Add Notification');
		$this->render('new', array(
				'entry'		=> $entry,
				'events'	=> $this->member()->event,
				'clients'	=> $this->member()->clients,

			));
	}

	public function actionDelete($notificationId = NULL)
	{
		$ids = array();
		if (isset($_POST['ids']) && count($_POST['ids'])) {
			$ids = $_POST['ids'];
		} elseif ($notificationId) {
			$ids = array(intval($notificationId));
		}

		foreach ($ids as $id)
		{
			$entry = ApplicationNotification::model()->findByPk($id);
			if ($entry->application_id == $this->member()->application->id)
			{
				$entry->delete();
			}
		}

		if (Yii::app()->getRequest()->isAjaxRequest)
		{
			$this->sendJsonSuccess();
		} else {
			$this->redirect(array('notification/'));
		}
	}

	public function actionIndex()
	{
		if (isset($_POST['Fields'])) {
			if (isset($_POST['SearchBarReset']) && $_POST['SearchBarReset']) {
				StudioMessage::model()->resetSearchFields();
			} else {
				StudioMessage::model()->setSearchFields($_POST['Fields']);
			}
			$this->redirect(array('notification/'));
		}
		$criteria = ApplicationNotification::model()->searchCriteria();

		$pagination = new CPagination(ApplicationNotification::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);
		$this->setMemberPageTitle('Notifications');

		$entries = ApplicationNotification::model()->findAll($criteria);

		$this->render('index',array(
			'entries'		=> $entries,
			'pagination'	=> $pagination,
			'searchOptions'	=> ApplicationNotification::model()->searchOptions(),
		));
	}


	/**
	 * Ajax action used to add client access to events
	 * @return void
	 */
	public function actionAddClient()
	{
		if (empty($_POST['client_id']) || empty($_POST['event_id']))
			return $this->sendJsonSuccess(array(
				'state' => false,
			));
		$obEvent = Event::model()->findByPk($_POST['event_id']);
		$obClient = Client::model()->findByPk($_POST['client_id']);
		if (!$obEvent || !$obClient)
			return $this->sendJsonSuccess(array(
				'state' => false,
			));
		return $this->sendJsonSuccess(array(
			'state' => $obClient->addPhotoEvent($obEvent),
		));
	}

	/**
	 * Ajax action used to remove client access to events
	 * @return void
	 */
	public function actionRemoveClient()
	{
		if (empty($_POST['client_id']) || empty($_POST['event_id']))
			return $this->sendJsonSuccess(array(
				'state' => false,
			));
		$obEvent = Event::model()->findByPk($_POST['event_id']);
		$obClient = Client::model()->findByPk($_POST['client_id']);

		if (!$obEvent || !$obClient)
			return $this->sendJsonSuccess(array(
				'state' => false,
			));
		return $this->sendJsonSuccess(array(
			'state' => $obClient->removePhotoEvent($obEvent),
		));
	}

	/**
	 * Ajax action used to add client access to events
	 * @return void
	 */
	public function actionAddEvent()
	{
		if (empty($_POST['client_id']) || empty($_POST['event_id']))
			return $this->sendJsonSuccess(array(
				'state' => false,
			));
		$obEvent = Event::model()->findByPk($_POST['event_id']);
		$obClient = Client::model()->findByPk($_POST['client_id']);
		if (!$obEvent || !$obClient)
			return $this->sendJsonSuccess(array(
				'state' => false,
			));
		return $this->sendJsonSuccess(array(
			'state' => $obClient->addPhotoEvent($obEvent),
		));
	}

	/**
	 * Ajax action used to remove client access to events
	 * @return void
	 */
	public function actionRemoveEvent()
	{
		if (empty($_POST['client_id']) || empty($_POST['event_id']))
			return $this->sendJsonSuccess(array(
				'state' => false,
			));
		$obEvent = Event::model()->findByPk($_POST['event_id']);
		$obClient = Client::model()->findByPk($_POST['client_id']);

		if (!$obEvent || !$obClient)
			return $this->sendJsonSuccess(array(
				'state' => false,
			));
		return $this->sendJsonSuccess(array(
			'state' => $obClient->removePhotoEvent($obEvent),
		));
	}
}