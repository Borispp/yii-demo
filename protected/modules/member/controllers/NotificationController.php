<?php
class NotificationController extends YsaMemberController
{
	public function init()
	{
		parent::init();
	}

	public function actionNew($recipient, $type = NULL)
	{
		$type = (empty($type) || !in_array($type, array('event', 'client', 'all', 'allipads'))) ? 'all' : $type;
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
			case 'allipads':
				$title .= 'all connected ipads';
				break;
			default:
				$title .= 'all clients';
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
		$entry = new IpadNotification();
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

	/**
	 * Send to one client
	 */
	public function actionSendToClient()
	{
		$notification = $this->_createNotification();
		if (empty($_POST['recipient']) || !($client = Client::model()->findByPk($_POST['recipient'])))
		{
			$notification->delete();
			return $this->_sendError('Client not found.');
		}

		if (($result = Ipad::model()->addNotificatonByClient($client, $notification)) !== TRUE)
		{
			$notification->delete();
			$this->_sendError($result);
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
		if (empty($event->clients))
		{
			$notification->delete();
			$this->_sendError('No clients with access to selected event');
		}
		$error = FALSE;
		foreach($event->clients as $client)
		{
			if (($result = Ipad::model()->addNotificatonByClient($client, $notification)) !== TRUE)
			{
				$error = TRUE;
			}
		}
		if ($error)
		{
			$notification->delete();
			$this->_sendError('No registered clients with access to selected event');
		}
		return $this->_sendSuccess();
	}

	/**
	 * Send to all clients (create one-by-one relation)
	 * @return void
	 */
	public function actionSendToAll()
	{
		$error = TRUE;
		$notification = $this->_createNotification();
		foreach($this->member()->client as $client)
		{
			if (($result = Ipad::model()->addNotificatonByClient($client, $notification)) == TRUE)
			{
				$error = FALSE;
			}
		}
		if ($error)
		{
			$notification->delete();
			$this->_sendError('No authorized clients found');
		}
		return $this->_sendSuccess();
	}

	/**
	 * Send to all users
	 * @return void
	 */
	public function actionSendToAllIpads()
	{
		$notification = $this->_createNotification();
		if (!Ipad::model()->addNotificationByApplication($this->member()->application, $notification))
		{
			$notification->delete();
			$this->_sendError('No connected ipads found');
		}
		return $this->_sendSuccess();
	}

	public function sendOne(ApplicationNotification $notification)
	{
		foreach($this->member()->application->ipadToken as $ipad)
		{
			//$ipad->
		}
	}

	public function sendAll()
	{

		try
		{
			Yii::createComponent('ext.ApnsPHP.ApnsPHP');
						// Instanciate a new ApnsPHP_Push object
			//Provider certificate file
			$push = new ApnsPHP_Push(
				ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
					Yii::getPathOfAlias('webroot.etc').'/server_certificates_bundle_sandbox.pem'
			);

			// Set the Root Certificate Autority to verify the Apple remote peer
			$push->setRootCertificationAuthority(Yii::getPathOfAlias('webroot.etc').'/entrust_root_certification_authority.pem');
			// Connect to the Apple Push Notification Service
			$push->connect();



			// Instantiate a new Message with a single recipient
			$message = new ApnsPHP_Message('1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485');

			// Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
			// over a ApnsPHP_Message object retrieved with the getErrors() message.
			$message->setCustomIdentifier("Message-Badge-3");

			// Set badge icon to "3"
			$message->setBadge(3);

			// Set a simple welcome text
			$message->setText('Hello APNs-enabled device!');

			// Play the default sound
			$message->setSound();

			// Set a custom property
			$message->setCustomProperty('acme2', array('bang', 'whiz'));

			// Set another custom property
			$message->setCustomProperty('acme3', array('bing', 'bong'));

			// Set the expiry value to 30 seconds
			$message->setExpiry(30);

			// Add the message to the message queue
			$push->add($message);

			// Send all messages in the message queue
			$push->send();

			// Disconnect from the Apple Push Notification Service
			$push->disconnect();
			echo 'sdadsa';
			varDump($push);die;
			// Examine the error message container
			$aErrorQueue = $push->getErrors();
			if (!empty($aErrorQueue)) {
				var_dump($aErrorQueue);
			}
		}
		catch(ApnsPHP_Exception $e){


		}
	}
}