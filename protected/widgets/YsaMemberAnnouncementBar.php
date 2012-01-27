<?php
class YsaMemberAnnouncementBar extends CWidget
{
	public function run()
	{
		if (Yii::app()->controller->id == 'announcement')
			return;
		$notifications = Notification::model()->getMemberNotifications(Member::model()->findByPk(Yii::app()->user->getId()));
		if (count($notifications) < 1)
		{
			return;
		}
		$msg = YsaHtml::tag('div', array('id' => 'announcements'));
		list($notification) = $notifications;
		$msg .= YsaHtml::tag('div', array('class' => 'announcement'));
		$msg .= $this->_renderTitle($notification->title);
		$msg .= $this->_render($notification->message);
		if (count($notifications) > 1)
		{
			$msg .= YsaHtml::link('view all '.count($notifications), array('announcement/'), array('class' => 'announcement-all', 'target' => '_blank'));
		}
		$msg .= YsaHtml::link('close', array('announcement/MarkRead/id/'.$notification->id), array('class' => 'announcement-close', 'target' => '_blank'));
		$msg .= YsaHtml::closeTag('div');
		$msg .= YsaHtml::closeTag('div');
		echo $msg;
	}

	protected function _renderTitle($title)
	{
		return YsaHtml::tag('div', array('class' => 'title')).$title.YsaHtml::closeTag('div');
	}
	protected function _render($message)
	{
		return YsaHtml::tag('div', array('class' => 'msg')).$message.YsaHtml::closeTag('div');
	}
}