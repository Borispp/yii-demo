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

		$login = new LoginForm;
		
		// collect user input data
		if(isset($_POST['LoginForm'])) {
			$login->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($login->validate() && $login->login()) {
				$this->redirect(array('//admin'));
			}
			
			// reset password
			$login->password = '';
		}
		
		// display the login form
		$this->render('login', array(
			'login'		=> $login,
		));
	}
	
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}