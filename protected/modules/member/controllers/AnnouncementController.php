<?php
class AnnouncementController extends YsaMemberController
{
	public function actionIndex()
	{
		$this->crumb(Yii::t('title', 'announcement'), array('announcement/'));
		$this->setMemberPageTitle(Yii::t('title', 'announcement'));
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