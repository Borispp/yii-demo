<?php
class AuthController extends YsaFrontController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		
		if (!isset($_GET['b51']) && $this->getAction()->getId() != 'logout') {
			$this->redirect(array('/comingsoon'));
		}
		
		return true;
	}
	
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (Yii::app()->user->isLoggedIn()) {
			$this->redirect(Yii::app()->user->returnUrl);
		}

		$login = new LoginForm;
		
		$register = new RegistrationForm;

		// collect user input data
		if(isset($_POST['LoginForm'])) {
			$login->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($login->validate() && $login->login()) {
				$this->redirect( $this->_urlToRedirectAuthenticated() );
			}
			
			// reset password
			$login->password = '';
		}
		
		if(isset($_POST['RegistrationForm'])) 
		{
			$register->attributes = $_POST['RegistrationForm'];
			$pass = $register->password;
			
			if ($register->register()) {
				
				$login->email = $register->email;
				$login->password = $pass;
				
				if($login->validate() && $login->login()) {
					
				}
				
				$this->setSuccess(Yii::t('register', 'first_login_welcome'));
				$this->redirect($this->_urlToRedirectAuthenticated());
			}
		}
		
		// always reset password fields
		$register->password = '';
		$register->verifyPassword = '';
		
		$page = Page::model()->findBySlug('login');
		
		$this->setFrontPageTitle(Yii::t('general', 'Login'));
		
		// display the login form
		$this->render('login', array(
			'login'		=> $login,
			'register'	=> $register,
			'page'		=> $page,
		));
	}

	public function actionLoginOauth()
	{
		$service = Yii::app()->request->getQuery('service');
		if (isset($service)) 
		{
			$options = array(
				'scope' => 'email,publish_stream,offline_access',
				'client_id' => Yii::app()->settings->get('facebook_app_id'),
				'client_secret' => Yii::app()->settings->get('facebook_app_secret')
			);
			$authIdentity = Yii::app()->eauth->getIdentity( $service, $options );
			$authIdentity->redirectUrl = Yii::app()->user->returnUrl;
			$authIdentity->cancelUrl = $this->createAbsoluteUrl('login');
			
			if ($authIdentity->authenticate())
			{
				$identity = new ServiceUserIdentity($authIdentity);
				
				// successful authentication
				if ($identity->authenticate()) 
				{
					Yii::app()->user->login($identity); //TODO: add if statement and skip password check somehow
					
					// special redirect with closing popup window
					$authIdentity->redirect( $this->_urlToRedirectAuthenticated() );
				}
				else 
				{
					Yii::app()->session['oauth_user_identity'] = $authIdentity;
					$this->setError(Yii::t('authentication', 'fb_unable_to_auth_with_reason') . $identity->errorMessage );
					// close popup window and redirect to cancelUrl
					$authIdentity->cancel($this->createAbsoluteUrl('/auth/completeOauthRegistration'), true);
				}
			}
			
			$this->setError(Yii::t('authentication', 'fb_unable_to_auth'));
			// Something went wrong, redirect to login page
			$this->redirect(array('/login/'));
		}
	}
	
	/**
	 * Runing in popup window
	 */
	public function actionOauthRegistration()
	{
		$service = Yii::app()->request->getQuery('service');
		if (isset($service)) 
		{
			$options = array(
				'scope' => 'email,publish_stream,offline_access',
				'client_id' => Yii::app()->settings->get('facebook_app_id'),
				'client_secret' => Yii::app()->settings->get('facebook_app_secret')
			);
			$authIdentity = Yii::app()->eauth->getIdentity( $service, $options );
			$authIdentity->redirectUrl = $this->createAbsoluteUrl('/auth/completeOauthRegistration');
			$authIdentity->cancelUrl = $this->createAbsoluteUrl('/login');

			if ($authIdentity->authenticate())
			{
				$identity = new ServiceUserIdentity($authIdentity);

				// try to authentificate with existing user
				if ( $identity->authenticate() ) 
				{
					Yii::app()->user->login( $identity );
					$authIdentity->redirect($this->_urlToRedirectAuthenticated());
				}
				else 
				{
					Yii::app()->session['oauth_user_identity'] = $authIdentity;
					
					// special redirect with closing popup window
					$authIdentity->redirect();
				}
			}
			$authIdentity->cancel();
		}
		
		//TODO: close popup
	}
	
	public function actionCompleteOauthRegistration()
	{
		if ( !isset(Yii::app()->session['oauth_user_identity']) )
			$this->redirect( $this->createAbsoluteUrl('/register') );

		$this->setFrontPageTitle(Yii::t('general', 'Login'));
		
		$reg_form = new RegistrationForm;
		$attr = Yii::app()->session['oauth_user_identity']->getItemAttributes();
		$reg_form->attributes = $safe_attr = array( 'first_name' => $attr['first_name'], 'last_name' => $attr['last_name'], 'email' => $attr['email'] );

		if ( !Yii::app()->request->isPostRequest )
		{
			// display the login form
			$this->render('login', array(
				'login'		=> new LoginForm,
				'register'	=> $reg_form,
				'page'		=> Page::model()->findBySlug('login'),
			));
			Yii::app()->end();
		}

		$reg_form->attributes = array_merge( $_POST['RegistrationForm'], $safe_attr );

		$transaction = Yii::app()->getDb()->beginTransaction();
		try
		{
			if ($reg_form->register(true, true))
			{
				$reg_form->linkFacebook($attr['id']);
				$reg_form->activate();
				$transaction->commit();

				$identity = new ServiceUserIdentity( Yii::app()->session['oauth_user_identity'] );
				if ($identity->authenticate()) 
				{
					Yii::app()->user->login( $identity );
					unset( Yii::app()->session['oauth_user_identity'] );
					$this->redirect($this->_urlToRedirectAuthenticated());
				}
				
				unset( Yii::app()->session['oauth_user_identity'] );
				$this->setError(App::t('autentication', 'fb_registered_but_not_logged_in'));
				$this->redirect(array('login/'));
			}
		}
		catch ( CException $e )
		{
			$transaction->rollback();
			throw $e;
		}
		
		// display the login form
		$this->render('login', array(
			'login'		=> new LoginForm,
			'register'	=> $reg_form,
			'page'		=> Page::model()->findBySlug('login'),
		));
	}
	
	/**
	 * @return string URL to redirect authentificated user
	 */
	protected function _urlToRedirectAuthenticated()
	{
		if (Yii::app()->user->isAdmin()) {
			return array('//admin');
		} elseif (Yii::app()->user->isMember()) {
			// $this->redirect(Yii::app()->user->returnUrl);
			return array('//member');
		} else {
			return array('//login');
		}
	}
	
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionActivate()
	{
		$k = isset($_GET['k']) ? $_GET['k'] : null;
		
		if (!$k) {
			$request = Yii::app()->getRequest();
			$this->setError( 'Incorrect activation URL: '.$request->getHostInfo().$request->getUrl() );
			$this->redirect($this->_urlToRedirectAuthenticated());
		}
		
		$user = Member::model()->findByAttributes(array('activation_key' => $k));
		
		if ($user && $user->isActivated()) 
		{
			$this->setNotice( 'You account is already activated' );
		} 
		elseif(isset($user->activation_key) && ($user->activation_key==$k)) 
		{
			$user->activate();
			$this->setSuccess( 'Your account was successfully activated' );
		} 
		else 
		{
			$this->setError( 'Unable to activate account, maybe key is invalid' );
		}
		
		$this->redirect($this->_urlToRedirectAuthenticated());
	}
	
	public function actionCheckRegistration()
	{
		if(isset($_POST['RegistrationForm']) && Yii::app()->request->isAjaxRequest)  {
			$register = new RegistrationForm();
			
			$register->attributes = $_POST['RegistrationForm'];
			
			if ($register->validate()) {
				$this->sendJsonSuccess();
			} else {
				$this->sendJsonError(array(
					'errors' => $register->prepareAjaxErrors(),
				));
			}
		}
		$this->redirect(array('/login'));
	}
}