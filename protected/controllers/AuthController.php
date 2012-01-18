<?php
class AuthController extends YsaFrontController
{
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (Yii::app()->user->isLoggedIn()) {
			$this->redirect(Yii::app()->user->returnUrl);
		}

		$model = new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm'])) {
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
				$this->redirect( $this->_urlToRedirectAuthenticated() );
			}
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	public function actionLoginOauth()
	{
		//TODO: strange bug: eauth not using __eauth_facebook__expires key 
		unset( Yii::app()->session['__eauth_facebook__auth_token'] );
		
		$service = Yii::app()->request->getQuery('service');
		if (isset($service)) 
		{
			$authIdentity = Yii::app()->eauth->getIdentity($service, array('scope' => 'email,publish_stream'));
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