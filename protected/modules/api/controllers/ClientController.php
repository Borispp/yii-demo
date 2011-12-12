<?php
class ClientController extends YsaApiController
{
	/**
	 * Auth type
	 * @var string
	 */
	protected $_type = 'client';

	protected function _validateAuth()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
			'token'	=> array(
				'code'		=> '006',
				'message'	=> 'No token received',
				'required'	=> TRUE,
		)));
		$this->_checkAuth();
	}

	/**
	 * Action client authorization.
	 * Inquiry params: [app_key, device_id, password]
	 * Response params: [token, state, message]
	 * @return void
	 */
	public function actionAuth()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
			'password'	=> array(
				'code'		=> '005',
				'message'	=> 'No password received',
				'required'	=> TRUE,
		)));
		if (!$token = ApplicationAuth::model()->authByPassword($_POST['password'], $_POST['app_key'], $_POST['device_id'], $this->_type))
			$this->_render(array(
				'state'		=> 0,
				'message'	=> 'Authorization by password failed',
				'token'		=> NULL,
			));
		$this->_render(array(
			'state'		=> 1,
			'message'	=> '',
			'token'		=> $token
		));
	}
}