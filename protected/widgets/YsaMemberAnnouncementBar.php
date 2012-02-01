<?php
class YsaMemberAnnouncementBar extends CWidget
{
	public function run()
	{
		if (!Yii::app()->controller->module || !Yii::app()->controller->module->id == 'member' || Yii::app()->controller->id == 'announcement')
			return;
		if (!Yii::app()->user->getId()  || !($user = Member::model()->findByPk(Yii::app()->user->getId())))
			return;
		$announcements = Announcement::model()->getMemberAnnouncements($user);
		if (count($announcements) < 1)
		{
			return;
		}
		echo YsaHtml::link(count($announcements), array('/member/announcement/'),  array('id' => 'announcements','title' => 'You have '.count($announcements).' new announcement'.(count($announcements) > 1 ? 's' : '')));
	}
}