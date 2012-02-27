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
				'message'	=> Yii::t('api', 'client_no_token'),
				'required'	=> TRUE,
			),
		));
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
					'message'	=> Yii::t('api', 'client_error_required', array('{field}' => 'name')),
					'required'	=> TRUE,
				),
				'email' => array(
					'message'	=> Yii::t('api', 'client_error_required', array('{field}' => 'email')),
					'required'	=> TRUE,
				),
				'password' => array(
					'message'	=> Yii::t('api', 'client_error_required', array('{field}' => 'password')),
					'required'	=> TRUE,
				),
			));

		$entry = new Client();
		$params = array(
			'name'				=> $_POST['name'],
			'email'				=> $_POST['email'],
			'password'			=> $_POST['password'],
			'added_with'		=> 'ipad',
			'state'				=> TRUE,
			'user_id'			=> $this->_getApplication()->user_id
		);
		if (!empty($_POST['phone']))
			$params['phone'] = $_POST['phone'];
		$entry->attributes = $params;
		if (!$entry->validate())
		{
			$this->_render(array(
					'state'		=> FALSE,
					'errors'	=> $entry->getErrors(),
					'message'	=> Yii::t('api', 'client_registration_failed'),
					'token'		=> NULL
				));
		}
		$entry->save();

		$this->_render(array(
				'state'		=> TRUE,
				'message'	=> NULL,
				'token'		=> ClientAuth::model()->authByPassword($_POST['email'], $_POST['password'], trim($_POST['app_key']), $_POST['device_id'])
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
					'message'	=> Yii::t('api', 'common_no_field', array('{field}' => 'password')),
					'required'	=> TRUE,
				),
				'email'	=> array(
					'message'	=> Yii::t('api', 'common_no_field', array('{field}' => 'email')),
					'required'	=> TRUE
				)
			));
		try
		{
			$this->_render(array(
					'state'		=> TRUE,
					'message'	=> '',
					'token'		=> ClientAuth::model()->authByPassword($_POST['email'], $_POST['password'], $_POST['app_key'], $_POST['device_id'])
				));
		}
		catch(YsaAuthException $e)
		{
			$this->_render(array(
					'state'		=> FALSE,
					'message'	=> $e->getMessage(),
					'token'		=> NULL,
				));
		}
	}

	public function actionRemindPassword()
	{
		$this->_validateVars(array(
			'email'	=> array(
				'message'  => Yii::t('api', 'common_no_field', array('{field}' => 'email')),
				'required' => TRUE
			)
		));
		$form = new RecoveryClientForm();
		$form->email = $_POST['email'];
		$form->user_id = $this->_getApplication()->user_id;
		if (!$form->validate())
		{
			$this->_render(array(
					'state' => FALSE,
					'message' => $form->getError('email'),
				));
		}
		$client = $form->getClient();
		$newPassword = YsaHelpers::genRandomString(6);
		$client->password = $newPassword;
		$client->save();
		$sent = Email::model()->send(
			array($client->email, $client->name),
			'client_recovery',
			array(
				'name'        => $client->name,
				'newpassword' => $newPassword,
			)
		);
		if (!$sent)
			$this->_render(array(
				'state' => FALSE,
				'message' => Yii::t('api', 'client_remind_password_mail_failed'),
			));
		$this->_render(array(
			'state' => TRUE,
			'message' => 'Email was send',
		));
	}

	protected function _validateFacebookVars()
	{
		$this->_validateVars(
			array(
				'fb_access_token'	=> array(
					'message'	=> Yii::t('api', 'common_no_field', array('{field}' => 'Facebook Access Token')),
					'required'	=> TRUE,
				),
				'fb_id'	=> array(
					'message'	=> Yii::t('api', 'common_no_field', array('{field}' => 'Facebook ID')),
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
				return $this->_renderError(Yii::t('api', 'client_facebook_token_invalid'));
		}
		catch (FacebookApiException $e)
		{
			return $this->_renderError(Yii::t('api', 'client_facebook_exception', array('{exception}' => $e->getMessage())));
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
						'state'		=> FALSE,
						'message'	=> Yii::t('api', 'client_facebook_no_account'),
						'token'		=> null,
					));
			}

			$this->_validateFacebookAccessToken( $_POST['fb_id'], $_POST['fb_access_token'] );

			$access_token = ClientAuth::model()->authByPassword($client->email, $client->password, $_POST['app_key'], $_POST['device_id']);

			return $this->_render(array(
					'state'		=> TRUE,
					'message'	=> '',
					'token'		=> $access_token
				));
		}
		catch(YsaAuthException $e)
		{
			$this->_render(array(
					'state'		=> FALSE,
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

		try
		{
			if ( !$client->linkFacebook( $_POST['fb_id'] ) )
			{
				$this->_render(array(
						'state'		=> FALSE,
						'message'	=> Yii::t('api', 'client_facebook_link_failed'),
					));
			}
		}
		catch( LogicException $e )
		{
			$this->_render(array(
					'state'		=> FALSE,
					'message'	=> $e->getMessage(),
				));
		}

		return $this->_render(
			array(
				'state'		=> TRUE,
				'message'	=> ''
			));
	}

	/**
	 * Unlink Facebook acc and send email with local login/password info
	 *
	 * @return boolean true on success
	 */
	public function actionUnlinkFacebook()
	{
		$this->_validateFacebookVars();
		$this->_validateFacebookAccessToken( $_POST['fb_id'], $_POST['fb_access_token'] );
		$client = $this->_validateAuth();

		if ( !$client->unlinkFacebook() )
		{
			$this->_render(array(
					'state'		=> FALSE,
					'message'	=> Yii::t('api', 'client_facebook_unlink_failed'),
				));
		}

		return $this->_render(
			array(
				'state'		=> TRUE,
				'message'	=> ''
			));
	}
}