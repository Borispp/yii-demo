<?php
class YsaNotificationBar extends CWidget
{
	public function run()
	{
		$msg = '';
		$app = Yii::app();
		
		if($app->user->hasFlash('success'))
			$msg = $this->_render($app, 'success');

		if ($app->user->hasFlash('notice'))
			$msg .= $this->_render($app, 'notice');
		
		if ($app->user->hasFlash('error'))
			$msg .= $this->_render($app, 'error');
		
		echo $msg;
	}
	
	protected function _render( $app, $msg_type )
	{
		return <<<"MSG"
			<div class="flash {$msg_type}">
				{$app->user->getFlash($msg_type)}
			</div>
MSG;
	}
}
