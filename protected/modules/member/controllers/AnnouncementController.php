<?php
class AnnouncementController extends YsaMemberController
{
	protected $_isAjax = FALSE;

	public function init()
	{
		parent::init();
		$this->renderVar('hideAnnouncementBar', TRUE);
		$this->crumb('Announcements', array('announcement/'));
		$this->_isAjax = Yii::app()->request->isAjaxRequest || isset($_GET['iframe']);
	}

	public function actionMarkRead($id)
	{
		if (empty($id))
		{
			if (!$this->_isAjax)
			{
				$this->redirect(array('announcement/'));
			}
			$this->sendJsonError();
		}
		$announcement = Announcement::model()->findByPk($id);
		if ($announcement->read($this->member()) && $this->_isAjax)
			$this->sendJsonSuccess();
		$this->redirect(array('announcement/'));
	}

	public function actionIndex()
	{
		$this->setMemberPageTitle('Announcements');
		$this->render('index', array(
			'announcements' => Announcement::model()->findAllByMember($this->member(), TRUE)
		));
	}
}