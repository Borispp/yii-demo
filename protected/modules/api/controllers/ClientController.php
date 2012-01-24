<?php
/**
 * Client controller
 *
 */
class ClientController extends YsaApiController
{
	protected function beforeAction($action)
	{
		$this->_commonValidate();
		return parent::beforeAction($action);
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
		try
		{
			$this->_render(array(
				'state'		=> 1,
				'message'	=> '',
				'token'		=> ClientAuth::model()->authByPassword($_POST['email'], $_POST['password'], $_POST['app_key'], $_POST['device_id'])
			));
		}
		catch(YsaAuthException $e)
		{
			$this->_render(array(
				'state'		=> 0,
				'message'	=> $e->getMessage(),
				'token'		=> NULL,
			));
		}
	}
	
	protected function _validateFacebookVars()
	{
		$this->_validateVars(
			array(
				'fb_access_token'	=> array(
					'message'	=> 'No Facebook Access Token received',
					'required'	=> TRUE,
				),
				'fb_id'	=> array(
					'message'	=> 'No Facebook ID received',
					'required'	=> TRUE,
				)
			));
	}
	
	/**
	 * Validate access token: 
	 * 1. Request, made with token must give valid response
	 * 2. Facebook Account ID in response must match against requested ID
	 * 
	 * @param integer $fb_id
	 * @param string $fb_access_token
	 * @return void 
	 */
	protected function _validateFacebookAccessToken( $fb_id, $fb_access_token )
	{
		Yii::import('ext.facebook-sdk.Facebook');
		$facebook = new Facebook(array(
			'appId'  => Yii::app()->params['oauth']['facebook_app_id'],
			'secret' => Yii::app()->params['oauth']['facebook_app_secret'],
		));
		
		try 
		{
			$facebook->setAccessToken( $fb_access_token );
			$user_profile = $facebook->api('/me');

			if ( $user_profile['id'] != $fb_id )
				return $this->_renderError('Access token is invalid: Facebook ID is not matches requested with Acess Token');
		} 
		catch (FacebookApiException $e) 
		{
			return $this->_renderError('Access token is invalid, Facebook raised exception: '.$e->getMessage());
		}
	}
	
	/**
	 * Try to auth with Facebook ID and Access Token
	 * 1. Facebook account must be linked to any local user account
	 * 2. Access token must be valid @see _validateFacebookAccessToken
	 */
	public function actionFacebookLogin()
	{
		try
		{
			$this->_validateFacebookVars();
			
			$client = Client::model()->findByAttributes( array('facebook_id' => $_POST['fb_id'], 'state' => Client::STATE_ACTIVE) );
			if (null === $client) 
			{
				// client profile not exists, need registration
				return $this->_render(array(
					'state'		=> false,
					'message'	=> 'Not found any linked Facebook account',
					'token'		=> null,
				));
			}
			
			$this->_validateFacebookAccessToken( $_POST['fb_id'], $_POST['fb_access_token'] );
			
			$access_token = ClientAuth::model()->authByPassword($client->email, $client->password, $_POST['app_key'], $_POST['device_id']);
			
			return $this->_render(array(
				'state'		=> true,
				'message'	=> '',
				'token'		=> $access_token
			));
		}
		catch(YsaAuthException $e)
		{
			$this->_render(array(
				'state'		=> false,
				'message'	=> $e->getMessage(),
				'token'		=> null,
			));
		}
	}
	
	public function actionLinkFacebook()
	{
		$this->_validateFacebookVars();
		$this->_validateFacebookAccessToken( $_POST['fb_id'], $_POST['fb_access_token'] );
		$client = $this->_validateAuth();
		
		$client->facebook_id = $_POST['fb_id'];
		if ( !$client->save() )
		{
			$this->_render(array(
				'state'		=> false,
				'message'	=> 'Unable to link Facebook account',
			));
		}
		
		return $this->_render(
			array(
				'state'		=> true,
				'message'	=> ''
			));
	}
	
	public function actionUnlinkFacebook()
	{
		$this->_validateFacebookVars();
		$this->_validateFacebookAccessToken( $_POST['fb_id'], $_POST['fb_access_token'] );
		$client = $this->_validateAuth();
		
		$client->facebook_id = '';
		if ( !$client->save( false ) )
		{
			$this->_render(array(
				'state'		=> false,
				'message'	=> 'Unable to unlink Facebook account',
			));
		}
		
		return $this->_render(
			array(
				'state'		=> true,
				'message'	=> ''
			));
	}
}