<?php
class YsaMemberAnnouncementBar extends CWidget
{
	public function run()
	{
		$msg = YsaHtml::tag('div', array('id' => 'announcement'));
		foreach(Notification::model()->getMemberNotifications($member = Member::model()->findByPk(Yii::app()->user->getId())) as $obNotification)
		{
			$msg .= $this->_renderTitle($obNotification->title);
			$msg .= $this->_render($obNotification->messsage);
			$obNotification->read($member);
			break;
		}
		$msg .= YsaHtml::closeTag('div');
		echo $msg;
	}


	protected function _renderTitle($title)
	{
		return YsaHtml::tag('div', array('class' => 'title')).YsaHtml::tag('h4').$title.YsaHtml::closeTag('h4').YsaHtml::closeTag('div');
	}
	protected function _render($message)
	{
		return YsaHtml::tag('div', array('class' => 'message')).$message.YsaHtml::closeTag('div');
	}
}