<?php
class SettingsController extends YsaMemberController
{
	public function actionIndex()
	{
		if (isset($_POST['Member'])) {

			$this->member()->attributes = $_POST['Member'];

			if ($this->member()->validate()) {
				$this->member()->save();

				$this->setSuccess('Settings successfully changed.');
				$this->refresh();
			}
		}

		$changePasswordForm = new SettingsChangePassword();

		if (isset($_POST['SettingsChangePassword'])) {
			$changePasswordForm->attributes = $_POST['SettingsChangePassword'];
			if ($changePasswordForm->validate()) {
				$this->member()->password = $changePasswordForm->newPassword;
				$this->member()->encryptPassword();
				$this->member()->save();
				$this->setSuccess('Password successfully changed.');
				$this->refresh();
			}
			$changePasswordForm->currentPassword = '';
			$changePasswordForm->newPassword = '';
			$changePasswordForm->repeatPassword = '';
		}

		$this->setMemberPageTitle('Settings');

		$this->crumb('Settings');

		$this->render('index', array(
				'entry'     => $this->member(),
				'password'  => $changePasswordForm,
			));
	}

	public function actionShootq()
	{
		$shootqForm = new ShootqApi();

		// set default ShootQ values
		$shootqForm->shootq_abbr = $this->member()->option('shootq_abbr');
		$shootqForm->shootq_key = $this->member()->option('shootq_key');
		$shootqForm->shootq_enabled = (int) $this->member()->option('shootq_enabled', 0);

		if (isset($_POST['ShootqApi'])) {
			$shootqForm->attributes = $_POST['ShootqApi'];
			if ($shootqForm->validate()) {
				foreach ($shootqForm->attributes as $name => $value) {
					$this->member()->editOption($name, $value);
				}
				$this->setSuccess('ShootQ settings successfully saved.');
				$this->refresh();
			}
		}

		$this->setMemberPageTitle('ShootQ Settings');

		$this->crumb('Settings', array('settings/'))
				->crumb('ShootQ');

		$this->render('shootq', array(
				'shootq'	=> $shootqForm,
			));
	}

	public function actionSmugmug($authorize = null)
	{
		$smugForm = new SmugMugApi();

		// set default ShootQ values
		$smugForm->smug_api = $this->member()->option(UserOption::SMUGMUG_API);
		$smugForm->smug_secret = $this->member()->option(UserOption::SMUGMUG_SECRET);

		if (isset($_POST['SmugMugApi'])) {
			$smugForm->attributes = $_POST['SmugMugApi'];
			if ($smugForm->validate()) {
				foreach ($smugForm->attributes as $name => $value) {
					$this->member()->editOption($name, $value);
				}

				$requestToken = $this->member()->smugmug()->auth_getRequestToken();
				//				$this->member()->editOption(UserOption::SMUGMUG_REQUEST, $requestToken);

				Yii::app()->session['smugmugRequestToken'] = $requestToken;

				$this->setSuccess('SmugMug settings successfully saved.');
				$this->refresh();
			}
		}

		if (isset(Yii::app()->session['smugmugRequestToken'])) {
			$this->member()->smugmugSetRequestToken(Yii::app()->session['smugmugRequestToken']);
		}

		if (isset($authorize)) {
			$token = $this->member()->smugmug()->auth_getAccessToken();
			unset(Yii::app()->session['smugmugRequestToken']);
			$this->member()->editOption(UserOption::SMUGMUG_HASH, $token);
			$this->redirect(array('settings/smugmug/'));
		}

		if ($this->member()->smugmugAuthorized()) {
			try {
				$this->member()->smugmugSetAccessToken();
			} catch (Exception $e) {
				$this->setError($e->getMessage());
				$this->redirect(array('settings/smugmug/'));
			}
		}

		$this->setMemberPageTitle('SmugMug Authentification');

		$this->crumb('Settings', array('settings/'))
				->crumb('SmugMug');

		$this->render('smugmug', array(
			'entry' => $this->member(),
			'smug'		=> $smugForm,
		));
	}

	public function actionSmugmugUnlink()
	{
		$this->member()->deleteOption(UserOption::SMUGMUG_REQUEST);
		$this->member()->deleteOption(UserOption::SMUGMUG_HASH);
		$this->member()->deleteOption(UserOption::SMUGMUG_AUTHORIZED);

		$this->setSuccess('SmugMug was successfully unlinked.');

		$this->redirect(array('settings/smugmug/'));
	}

	public function actionZenfolio()
	{
		$loginForm = new ZenFolioLogin();

		if ($this->member()->zenfolioAuthorized() && $this->member()->zenfolioAuthorize()) {

		} else {
			if (isset($_POST['ZenFolioLogin'])) {
				$loginForm->attributes = $_POST['ZenFolioLogin'];
				if ($loginForm->validate()) {
					try {
						$this->member()->zenfolio()->login("Username=" . $loginForm->username, "Password=" . $loginForm->password); // "Plaintext=TRUE"
						$this->member()->editOption(UserOption::ZENFOLIO_HASH, $this->member()->zenfolio()->getAuthToken());
						$this->setSuccess('ZenFolio was successfully authorized.');
						$this->refresh();
					} catch (Exception $e) {
						$loginForm->addError('username', 'Invalid credentials. Please try again.');
					}
				}
			}
		}

		$this->setMemberPageTitle('ZenFolio Authentification');

		$this->crumb('Settings', array('settings/'))
				->crumb('ZenFolio');

		$this->render('zenfolio', array(
			'zenlogin'	=> $loginForm,
			'entry'		=> $this->member(),
		));
	}

	public function action500px()
	{
		// set oauth token
		if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) && isset(Yii::app()->session['500pxRequestToken']['oauth_token_secret'])) {
			$this->member()->five00pxAuthorize(
				$_GET['oauth_token'],
				Yii::app()->session['500pxRequestToken']['oauth_token_secret'],
				$_GET['oauth_verifier']
			);
			
			if (isset(Yii::app()->session['500pxRequestToken'])) {
				unset(Yii::app()->session['500pxRequestToken']);
			}
			
			$this->setSuccess('Successfully authorized to 500px API.');
			$this->redirect(array('settings/500px/'));
		}
		
		// check token
		if (!$this->member()->five00pxAuthorized()) {
			// set request token
			if (!isset(Yii::app()->session['500pxRequestToken'])) {
				$token = $this->member()->five00px()->getRequestToken();
				Yii::app()->session['500pxRequestToken'] = $token;
				$this->refresh();
			}
		}
		
		$this->setMemberPageTitle('500px Authentification');
		
		$this->render('500px');
	}
	
	public function action500pxUnlink()
	{
		$this->member()->deleteOption(UserOption::FIVE00_HASH);

		if (isset(Yii::app()->session['500pxRequestToken'])) {
			unset(Yii::app()->session['500pxRequestToken']);
		}
		
		$this->setSuccess('500px was successfully unlinked.');

		$this->redirect(array('settings/500px/'));
	}
	
	public function actionFacebook()
	{
		$this->setMemberPageTitle('Facebook Account');
		
		$this->crumb('Settings', array('settings/'))
				->crumb('Facebook');
		
		$fb_form = new FacebookSettingsForm;
		$fb_form->email = $this->member()->option(UserOption::FACEBOOK_EMAIL, false);
		$fb_form->fb_id = $this->member()->option(UserOption::FACEBOOK_ID, false);
		
		$this->render('facebook', array( 'member' => $this->member(), 'fb' => $fb_form));
	}
	
	public function actionFacebookConnect()
	{
		$authIdentity = Yii::app()->eauth->getIdentity( 'facebook', array('scope' => 'email'));
		$authIdentity->redirectUrl = $this->createAbsoluteUrl('settings/facebook');
		$authIdentity->cancelUrl = $this->createAbsoluteUrl('settings/facebook');

		if ( $authIdentity->authenticate() ) 
		{
				$this->member()->editOption(UserOption::FACEBOOK_EMAIL, $authIdentity->getAttribute('email'));
				$this->member()->editOption(UserOption::FACEBOOK_ID, $authIdentity->getAttribute('id'));

				$this->setSuccess( 'Facebook account was successfully linked' );
				
				// special redirect with closing popup window
				$authIdentity->redirect();
		}
		else {
			// close popup window and redirect to cancelUrl
			$authIdentity->cancel();
		}
	}
	
	public function actionFacebookUnlink()
	{
		if ( ! $this->member()->deleteOptions( array( UserOption::FACEBOOK_EMAIL, UserOption::FACEBOOK_ID) ) )
			$this->setError( 'Unable to unlink Facebook account' );
		
		$this->setSuccess( 'Facebook account was successfully unlinked' );
		$this->redirect(array('settings/'));
	}
}