<?php
class AuthController extends YsaFrontController
{
	/**
	* Declares class-based actions.
	*/
	public function actions()
	{
		return array(
			'captcha'=>array(
			 'class'=>'CCaptchaAction',
			 'backColor'=>0xFFFFFF,
			),
		);
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
			if ($register->register()) {
				$this->setSuccess( 'Thank you for your registration. Please check your email' );
				$this->redirect(array('login/'));
			}
			
			$register->password = '';
			$register->verifyPassword = '';
		}
		
		
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
					$this->setError( 'Unable to authenticate: '.$identity->errorMessage );
					// close popup window and redirect to cancelUrl
					$authIdentity->cancel( null, true );
				}
			}
			
			$this->setError( 'Unable to authenticate' );
			// Something went wrong, redirect to login page
			$this->redirect(array('/login/'));
		}
	}
	
	/**
	 * @return string URL to redirect authentificated user
	 */
	protected function _urlToRedirectAuthenticated()
	{
		if (Yii::app()->user->isAdmin()) {
			return $this->createUrl('//admin', array());
		} else {
			// $this->redirect(Yii::app()->user->returnUrl);
			return $this->createUrl('//member', array());
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
			$this->redirect( $this->createAbsoluteUrl('login') );
		}
		
		$user = User::model()->findByAttributes(array('activation_key' => $k));
		
		if ($user && $user->state) 
		{
			$this->setNotice( 'You account is already activated' );
			$this->redirect( $this->createAbsoluteUrl('login') );
		} 
		elseif(isset($user->activation_key) && ($user->activation_key==$k)) 
		{
			$user->activate();			
			$this->setSuccess( 'Your account was successfully activated' );
			$this->redirect( $this->createAbsoluteUrl('login') );
		} 
		else 
		{
			$this->setError( 'Unable to activate account, maybe key is invalid' );
			$this->redirect( $this->createAbsoluteUrl('login') );
		}
	}
}