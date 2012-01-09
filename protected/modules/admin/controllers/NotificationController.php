<?php

class NotificationController extends YsaAdminController
{
	public function actionAdd()
	{
		$entry = new Notification();
		if(isset($_POST['Notification'])) {
			$entry->attributes = $_POST['Notification'];
			if ($this->_checkNotificationMembers() && $entry->validate()) {
				$entry->save();
				$this->_addMemberRelations($entry);
				$this->setSuccessFlash("New notification successfully added. " . CHtml::link('Back to listing.', array('index')));
				$this->redirect(array('index'));
			}
		}
		$this->setContentTitle('Add New Notification');
		$this->render('add', array(
				'entry'		=> $entry,
				'members'	=> Member::model()->findAllByAttributes(array(
					'state' => 1,
					'role' => 'member'
				))
			));
	}

	protected function _checkNotificationMembers()
	{
		if (!empty($_POST['members-all']))
			return TRUE;
		if (!empty($_POST['members']) && is_array($_POST['members']) && count($_POST['members']))
			return TRUE;
		$this->renderVar('membersError', TRUE);
		return FALSE;
	}

	protected function _addMemberRelations(Notification $obNotification)
	{
		$memberList = empty($_POST['members']) ? array() : $_POST['members'];
		if (!empty($_POST['members-all']))
		{
			$memberList = array();
			foreach(Member::model()->findAllByAttributes(array('state' => 1,'role' => 'member')) as $obMember)
			{
				$memberList[] = $obMember->id;
			}
		}
		foreach($memberList as $memberId)
		{
			$obNotificationUser = new NotificationUser();
			$obNotificationUser->user_id = $memberId;
			$obNotificationUser->notification_id = $obNotification->id;
			if ($obNotificationUser->validate())
				$obNotificationUser->save();
		}
	}

	public function actionDelete()
	{
		$ids = array();
		if (isset($_POST['ids']) && count($_POST['ids'])) {
			$ids = $_POST['ids'];
		} elseif (isset($_GET['id'])) {
			$ids = array(intval($_GET['id']));
		}
		foreach ($ids as $id)
		{
			Notification::model()->findByPk($id)->delete();
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}

	public function actionView($id)
	{
		$entry = Notification::model()->findByPk($id);
		if (!$entry)
		{
			$this->redirect(array('index'));
		}
		$this->setContentTitle($entry->title);
		$this->render('view', array(
				'entry'		=> $entry
			));
	}

	public function actionIndex()
	{

		$entries = Notification::model()->findAll();
		$this->setContentTitle('Notifications');
		$this->setContentDescription('Manage notifications to members.');
		$this->render('index', array(
				'entries' => $entries,
			));
	}
}