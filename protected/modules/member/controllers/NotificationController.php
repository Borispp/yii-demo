<?php
class NotificationController extends YsaMemberController
{
	public function init()
	{
		parent::init();
	}
	
	public function actionNew()
	{
		return $this->renderPartial('new', array(
			'type'		=> empty($_GET['type']) || !in_array($_GET['type'], array('event', 'client', 'all')) ? 'all' : $_GET['type'],
			'recipient'	=> $_GET['recipient']
		));
	}

	/**
	 * @todo make three methods to send notification with
	 * @return void
	 */
	public function actionSend()
	{
		$commonError = 'Notification send failed. Please refresh window and try again. If you see this message several times fill free to contact us.';
		if (!empty($_POST['type']) && in_array($_POST['type'], array('event', 'client')) && empty($_POST['recipient']))
		{
			return $this->sendJson(array('state' => FALSE, 'message' => $commonError));
		}
		if (empty($_POST['message']))
		{
			return $this->sendJson(array('state' => FALSE, 'message' => 'Message is empty. Please fill it up and try again.'));
		}
		$entry = new ApplicationNotification();
		$entry->application_id = $this->member()->application->id;
		$entry->message = $_POST['message'];
		if (!$entry->validate())
		{
			return $this->sendJson(array('state' => FALSE, 'message' => $commonError));
		}
		$entry->save();
		if ($_POST['type'] == 'event')
		{
			$obEvent = Event::model()->findByPk($_POST['recipient']);
			if (!$obEvent->isActive() || $obEvent->user_id != $this->member()->id)
			{
				$entry->delete();
				return $this->sendJson(array('state' => FALSE, 'message' => 'You can\'t send notification to this event viewers.'));
			}
			$entry->appendToEvent($obEvent);
		}
		elseif ($_POST['type'] == 'client')
		{
			$obClient = Client::model()->findByPk($_POST['recipient']);
			if (!$obClient->isActive() || $obClient->user_id != $this->member()->id)
			{
				$entry->delete();
				return $this->sendJson(array('state' => FALSE, 'message' => 'You can\'t send notification to this client.'));
			}
			$entry->appendToClient($obClient);
		}
		else
		{
			foreach($this->member()->client as $obClient)
			{
				if ($obClient->isActive())
					$entry->appendToClient($obClient);
			}
		}
		return $this->sendJson(array('state' => TRUE, 'message' => 'Your notification has been sent.'));
	}
}