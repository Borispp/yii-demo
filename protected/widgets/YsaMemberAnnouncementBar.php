<?php
class YsaMemberAnnouncementBar extends CWidget
{
	public function run()
	{
		$msg = YsaHtml::tag('div', array('id' => 'announcements'));
		foreach(Notification::model()->getMemberNotifications(Member::model()->findByPk(Yii::app()->user->getId())) as $obNotification)
		{
			$msg .= YsaHtml::tag('div', array('class' => 'announcement'));
			$msg .= $this->_renderTitle($obNotification->title);
			$msg .= $this->_render($obNotification->message);
			$msg .= YsaHtml::closeTag('div');
			break;
		}
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