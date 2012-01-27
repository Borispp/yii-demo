<?php
class YsaNotificationBar extends CWidget
{
	public function run()
	{
		$msg = YsaHtml::tag('div', array('id' => 'notifications'));
		
		$app = Yii::app();
		
		$types = array('success', 'notice', 'error', 'info');
		
		foreach ($types as $type) {
			if($app->user->hasFlash($type)) {
				$msg .= $this->_render($app->user->getFlash($type), $type);
				break;
			}
		}
		
		$msg .= YsaHtml::closeTag('div');
		echo $msg;
	}
	
	protected function _render($msg, $type)
	{
		return YsaHtml::tag('div', array('class' => 'app flash ' . $type)). $msg .YsaHtml::closeTag('div');
	}
}
