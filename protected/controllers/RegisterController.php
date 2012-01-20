<?php
class RegisterController extends YsaFrontController
{
	public $defaultAction = 'registration';
	
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
	
	public function init() {
		parent::init();
		
		if (Yii::app()->user->isLoggedIn()) {
			$this->redirect(Yii::app()->user->returnUrl);
		}
	}

	public function actionRegistration()
	{
		$model = new RegistrationForm('register');

		// uncomment the following code to enable ajax-based validation
		/*
		if(isset($_POST['ajax']) && $_POST['ajax']==='registration-form-registration-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		*/

		if(isset($_POST['RegistrationForm'])) 
		{
			$model->attributes = $_POST['RegistrationForm'];
			if ( $this->_register($model) )
			{
				$this->setSuccess( 'Thank you for your registration. Please check your email' );
				$this->redirect( $this->createAbsoluteUrl( 'auth/login' ) );
			}
		}
		$this->render('registration',array('model' => $model));
	}
	
	/**
	 *
	 * @param RegistrationForm $model
	 * @return boolean 
	 */
	protected function _register( RegistrationForm $model, $confirm_email = true )
	{
		if( !$model->validate() ) 
			return false;
		
		$model->state = User::STATE_INACTIVE;
		$model->role = User::ROLE_MEMBER;
		$model->encryptPassword();
		$model->generateActivationKey();

		if ( !$model->save(false) )
			return false;

		// send confirmation email
		if ( $confirm_email )
		{
			Email::model()->send(
				array($model->email, $model->name()), 
				'member_confirmation', 
				array(
					'name'	=> $model->name(),
					'email' => $model->email,
					'link'	=> $model->getActivationLink(),
				)
			);
		}

		// create new Studio
		$studio = new Studio();
		$studio->user_id = $model->id;
		$studio->save();

		return true;
	}

	/**
	 * Runing in popup window
	 */
	public function actionOauth()
	{
		$service = Yii::app()->request->getQuery('service');
		if (isset($service)) 
		{
			$authIdentity = Yii::app()->eauth->getIdentity($service, array('scope' => 'email,publish_stream,offline_access'));
			$authIdentity->redirectUrl = $this->createAbsoluteUrl('/register/oauth/complete');
			$authIdentity->cancelUrl = $this->createAbsoluteUrl('/register');

			if ($authIdentity->authenticate())
			{
				$identity = new ServiceUserIdentity($authIdentity);

				// try to authentificate with existing user
				if ( $identity->authenticate() ) 
				{
					Yii::app()->user->login( $identity );
					$authIdentity->redirect( $this->createAbsoluteUrl( 'auth/login' ) );
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
	
	public function actionOauthComplete()
	{
		if ( !isset(Yii::app()->session['oauth_user_identity']) )
			$this->redirect( $this->createAbsoluteUrl('/register') );

		$model = new OauthRegistrationForm('register');
		$attr = Yii::app()->session['oauth_user_identity']->getItemAttributes();
		$model->attributes = $safe_attr = array( 'first_name' => $attr['first_name'], 'last_name' => $attr['last_name'], 'email' => $attr['email'] );

		if ( ! Yii::app()->request->isPostRequest )
		{
			$this->render('oauth',array('model' => $model));
			Yii::app()->end();
		}

		$model->attributes = array_merge( $_POST['OauthRegistrationForm'], $safe_attr );

		if ( $this->_register( $model, false ) )
		{
			$model->linkFacebook( $attr['id'] );
			$model->activate();

			$identity = new ServiceUserIdentity( Yii::app()->session['oauth_user_identity'] );
			if ($identity->authenticate()) 
			{
				Yii::app()->user->login( $identity );
				unset( Yii::app()->session['oauth_user_identity'] );
				$this->redirect( $this->createAbsoluteUrl( 'auth/login' ) );
			}

			unset( Yii::app()->session['oauth_user_identity'] );
			$this->setError( 'Unable to immediately authenticate, please log in' );
			$this->redirect( $this->createAbsoluteUrl( 'auth/login' ) );
		}

		$this->render('oauth', array('model' => $model));
	}
}