<?php
class NotificationController extends YsaMemberController
{
	public function init()
	{
		parent::init();
	}

	public function actionNew($recipient, $type = NULL)
	{
		$type = (empty($type) || !in_array($type, array('event', 'client', 'all'))) ? 'all' : $type;
		$title = 'Send Push Notification to ';
		switch ($type)
		{
			case 'event':
				$event = Event::model()->findByPk($recipient);
				$title .= $event->name;
				break;
			case 'client':
				$client = Client::model()->findByPk($recipient);
				$title .= $client->name;
				break;
			default:
				$title .= 'all';
		}
		if (Yii::app()->request->isAjaxRequest || isset($_GET['iframe'])) {
			$this->renderPartial('new', array(
				'formAction' => Yii::app()->createAbsoluteUrl('/member/notification/sendto'.$type),
				'recipient'  => $recipient,
				'title'      => $title
			));
			Yii::app()->end();
		}

		$this->setMemberPageTitle('Send Push Notification');
		if ($type == 'event')
			$this->crumb('Events', array('event/'));
		else
			$this->crumb('Clients', array('client/'));
		$this->crumb('Send Push Notification');

		$this->render('new', array(
				'title'      => $title,
				'formAction' => Yii::app()->createAbsoluteUrl('/member/notification/sendto'.$type),
				'recipient'  => $recipient
			));
	}

	/**
	 * @return ApplicationNotification|void
	 */
	protected function _createNotification()
	{
		$entry = new ApplicationNotification();
		$entry->application_id = $this->member()->application->id;
		$entry->message = @$_POST['message'];
		if (!$entry->validate())
		{
			$this->_sendError($entry->getError('message'));
		}
		$entry->save();
		return $entry;
	}

	protected function _sendSuccess()
	{
		return $this->sendJson(array('state' => TRUE, 'message' => 'Your notification has been sent.'));
	}

	protected function _sendError($message = 'Notification send failed. Please refresh window and try again. If you see this message several times fill free to contact us.')
	{
		return $this->sendJson(array('state' => FALSE, 'message' => $message));
	}

	public function actionSendToClient()
	{
		$notification = $this->_createNotification();
		if (empty($_POST['recipient']) || !($client = Client::model()->findByPk($_POST['recipient'])))
		{
			$notification->delete();
			return $this->_sendError('Client not found.');
		}
		if (($result = $notification->appendToClient($client)) !== TRUE)
		{
			$errorText = '';
			foreach($result as $errors)
			{
				foreach($errors as $error)
				{
					$errorText .= ($errorText ? ' and ' : '').$error;
				}
			}
			$notification->delete();
			$this->_sendError($errorText);
		}
		$this->_sendSuccess();
	}

	public function actionSendToEvent()
	{
		$notification = $this->_createNotification();
		if (empty($_POST['recipient']) || !($event = Event::model()->findByPk($_POST['recipient'])))
		{
			$notification->delete();
			return $this->_sendError('Event not found.');
		}
		//@todo Make better errors handling
		if (($result = $notification->appendToEvent($event)) !== TRUE)
		{
			$errorText = '';
			foreach($result as $errors)
			{
				foreach($errors as $error)
				{
					$errorText .= ($errorText ? ' and ' : '').$error;
				}
			}
			$notification->delete();
			$this->_sendError($errorText);
		}
		return $this->_sendSuccess();
	}

	/**
	 * Send to all clients (create one-by-one relation)
	 * @return void
	 */
	public function actionSendToAll()
	{
		$entry = $this->_createNotification();
		foreach($this->member()->client as $obClient)
		{
			if ($obClient->isActive())
				$entry->appendToClient($obClient);
		}
		$this->_sendSuccess();
	}
}