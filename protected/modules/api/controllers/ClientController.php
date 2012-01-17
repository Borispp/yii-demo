<?php
/**
 * Client controller
 *
 */
class ClientController extends YsaApiController
{
	public function init()
	{
		parent::init();
		$this->_commonValidate();
	}

	/**
	 * Action client authorization.
	 * Inquiry params: [app_key, device_id, token]
	 * Response params: [state]
	 * @return void
	 */
	public function actionLogout()
	{
		$this->_validateVars(array(
			'token'		=> array(
				'message'	=> 'No token received',
				'required'	=> TRUE,
				'event_id'	=> array(
					'code'		=> '111',
					'message'	=> 'No event ID found',
					'required'	=> TRUE
				)
		)));
		$obClientAuthList = ClientAuth::model()->findAllByAttributes(array(
				'device_id'	=> $_POST['device_id'],
				'token'		=> $_POST['token'],
			));
		if (!$obClientAuthList)
			return;
		$obClientAuthList = is_array($obClientAuthList) ? $obClientAuthList : array($obClientAuthList);
		foreach($obClientAuthList as $obClientAuth)
			$obClientAuth->delete();
		$this->_render(array('state' => TRUE));
	}

	/**
	 * Register Client
	 * Inquiry params: [device_id, app_key, name, email, password, phone]
	 * Response params: [state, message, token]
	 * @return void
	 */
	public function actionRegister()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
				'name' => array(
					'message'	=> 'Name is required',
					'required'	=> TRUE,
				),
				'email' => array(
					'message'	=> 'Email is required',
					'required'	=> TRUE,
				),
				'password' => array(
					'message'	=> 'Password is required',
					'required'	=> TRUE,
				),
				'phone' => array(
					'message'	=> 'Phone is optional',
				),
			));

		$entry = new Client();
		$params = array(
			'name'				=> $_POST['name'],
			'email'				=> $_POST['email'],
			'password'			=> $_POST['password'],
			'added_with'		=> 'ipad',
			'state'				=> 1,
			'client_id'			=> $this->_getApplication()->user_id
		);
		if (!empty($_POST['phone']))
			$params['phone'] = $_POST['phone'];
		$entry->attributes = $params;
		if (!$entry->validate())
		{
			$this->_render(array(
					'state'		=> FALSE,
					'errors'	=> $entry->getErrors(),
					'message'	=> 'User registration failed',
					'token'		=> NULL
				));
		}
		$entry->save();

		$this->_render(array(
				'state'		=> TRUE,
				'message'	=> NULL,
				'token'		=> ClientAuth::model()->authByPassword($_POST['email'], $_POST['password'], $_POST['app_key'], $_POST['device_id'])
			));
	}

	/**
	 * Client Login By email and password
	 * Inquiry params: [device_id, app_key, email, password]
	 * Response params: [token, state, message]
	 * @return void
	 */
	public function actionLogin()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
				'password'	=> array(
					'message'	=> 'No password received',
					'required'	=> TRUE,
				),
				'email'	=> array(
					'message'	=> 'No email received',
					'required'	=> TRUE
				)
			));
		if (!$token = ClientAuth::model()->authByPassword($_POST['email'], $_POST['password'], $_POST['app_key'], $_POST['device_id']))
			$this->_render(array(
					'state'		=> 0,
					'message'	=> 'Login failed',
					'token'		=> NULL,
				));
		$this->_render(array(
				'state'		=> 1,
				'message'	=> '',
				'token'		=> $token
			));
	}
}