<?php
class ClientController extends YsaApiController
{
	/**
	 * Auth type
	 * @var string
	 */
	protected $_type = 'client';

	public function actionIndex()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
				'token'	=> array(
					'code'		=> '006',
					'message'	=> 'No token received',
					'required'	=> TRUE,
				)));
		$this->_checkAuth($_POST['token'], $_POST['device_id'], $_POST['app_id']);
	}

	public function actionAuth()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
				'password'	=> array(
					'code'		=> '005',
					'message'	=> 'No password received',
					'required'	=> TRUE,
				)));
		$model = $this->_registerAuth($_POST['password'], $_POST['device_id'], $_POST['app_id']);
		$this->_render(array(
				'state'		=> 1,
				'message'	=> '',
				'token'		=> $model->token
			));
	}
}