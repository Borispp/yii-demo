<?php
class InboxController extends YsaMemberController
{
	public function init()
	{
		parent::init();
		$this->crumb('Studio', array('studio/'))
			 ->crumb('Inbox', array('inbox/'));
	}

	public function actionIndex()
	{
		$this->setMemberPageTitle(Yii::t('title', 'inbox'));
		if (isset($_POST['Fields'])) {
			if (isset($_POST['SearchBarReset']) && $_POST['SearchBarReset']) {
				StudioMessage::model()->resetSearchFields();
			} else {
				StudioMessage::model()->setSearchFields($_POST['Fields']);
			}
			$this->redirect(array('inbox/'));
		}
		$criteria = StudioMessage::model()->searchCriteria();

		$pagination = new CPagination(StudioMessage::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = StudioMessage::model()->findAll($criteria);

		$this->render('index',array(
			'entries'       => $entries,
			'pagination'    => $pagination,
			'searchOptions' => StudioMessage::model()->searchOptions(),
		));
	}

	public function actionView($messageId)
	{
		$obStudioMessage = StudioMessage::model()->findByPk($messageId);
		if (!$obStudioMessage)
			return $this->render('error',array(
					'message'	=> 'No message with given ID found'
				));
		if ($obStudioMessage->user_id != $this->member()->id)
			return $this->render('error',array(
					'message'	=> 'Access denied'
				));
		if ($obStudioMessage->unread)
		{
			$obStudioMessage->unread = 0;
			$obStudioMessage->save();
		}
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/notification_button.js', CClientScript::POS_HEAD);
		$this->setMemberPageTitle($obStudioMessage->subject);
		$this->crumb($obStudioMessage->subject);
		$this->renderVar('entry', $obStudioMessage);
		$this->render('view');
	}

	public function actionDelete($messageId)
	{
		$obStudioMessage = StudioMessage::model()->findByPk($messageId);
		if (!$obStudioMessage)
			return $this->render('error',array(
					'message'	=> 'No message with given ID found'
				));
		if ($obStudioMessage->user_id != $this->member()->id)
			return $this->render('error',array(
					'message'	=> 'Access denied'
				));
		$obStudioMessage->delete();
		$this->redirect(array('inbox/'));
	}
}