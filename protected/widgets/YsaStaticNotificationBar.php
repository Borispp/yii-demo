<?php
class YsaStaticNotificationBar extends CWidget
{
	public function run()
	{
		$msg = YsaHtml::tag('div', array('id' => 'static-notifications'));
		
		$app = Yii::app();
		
		$types = array('success', 'notice', 'error', 'info');
		
		foreach ($types as $type) {
			if($app->user->hasFlash('static' . ucfirst($type))) {
				$msg .= $this->_render($app->user->getFlash('static' . ucfirst($type)), $type);
				break;
			}
		}
		
		$msg .= YsaHtml::closeTag('div');
		echo $msg;
	}
	
	protected function _render($msg, $type)
	{
		return YsaHtml::tag('div', array('class' => 'static-flash ' . $type)). $msg .YsaHtml::closeTag('div');
	}
}
