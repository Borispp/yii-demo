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

	public function actionIndex()
	{
		$this->setMemberPageTitle('Announcements');
		$announcementList = Announcement::model()->getMemberAnnouncements($this->member(), TRUE);
		foreach($announcementList as $announcement)
		{
			$announcement->read($this->member());
		}
		$this->render('index', array(
			'announcements' => $announcementList
		));
	}
}