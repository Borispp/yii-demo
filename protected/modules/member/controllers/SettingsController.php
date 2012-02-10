<?php
class SettingsController extends YsaMemberController
{
	public function actionIndex()
	{
		if (isset($_POST['Member'])) {
			$this->member()->attributes = $_POST['Member'];

			if ($this->member()->validate()) {
				$this->member()->save();
				$this->setSuccess(Yii::t('save', 'settings_changed'));
				$this->refresh();
			}
		}

		if (isset($_POST['notify_by_email']))
		{
			$this->member()->editOption('notify_by_email', isset($_POST['notify_by_email']));
		}

		$changePasswordForm = new SettingsChangePassword();

		if (isset($_POST['SettingsChangePassword'])) {
			$changePasswordForm->attributes = $_POST['SettingsChangePassword'];
			if ($changePasswordForm->validate()) {
				$this->member()->password = $changePasswordForm->newPassword;
				$this->member()->encryptPassword();
				$this->member()->save();
				$this->setSuccess(Yii::t('save', 'settings_password_changed'));
				$this->refresh();
			}
			$changePasswordForm->currentPassword = '';
			$changePasswordForm->newPassword = '';
			$changePasswordForm->repeatPassword = '';
		}

		$this->setMemberPageTitle(Yii::t('title', 'settings'));

		$this->crumb('Settings');

		$this->render('index', array(
				'entry'           => $this->member(),
				'password'        => $changePasswordForm,
				'notify_by_email' => $this->member()->option('notify_by_email', FALSE)
			));
	}

	public function actionShootq()
	{
		$shootqForm = new ShootqApi();

		// set default ShootQ values
		$shootqForm->shootq_abbr = $this->member()->option('shootq_abbr');
		$shootqForm->shootq_key = $this->member()->option('shootq_key');
		$shootqForm->shootq_enabled = (int) $this->member()->option('shootq_enabled', 0);

		if (isset($_POST['ShootqApi'])) 
		{
			$shootqForm->attributes = $_POST['ShootqApi'];
			if ($shootqForm->validate()) 
			{
				foreach ($shootqForm->attributes as $name => $value) {
					$this->member()->editOption($name, $value);
				}
				$this->setSuccess(Yii::t('save', 'settings_shootq_saved'));
				$this->member()->activate();
				$this->refresh();
			}
		}

		$this->setMemberPageTitle(Yii::t('title', 'settings_shootq'));

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

		if (isset($_POST['SmugMugApi'])) 
		{
			$smugForm->attributes = $_POST['SmugMugApi'];
			if ($smugForm->validate()) 
			{
				foreach ($smugForm->attributes as $name => $value) {
					$this->member()->editOption($name, $value);
				}

				try
				{
					$requestToken = $this->member()->smugmug()->auth_getRequestToken();
					//				$this->member()->editOption(UserOption::SMUGMUG_REQUEST, $requestToken);
				}
				catch(PhpSmugException $e)
				{
					$this->setError($e->getMessage());
					$this->refresh();
				}

				Yii::app()->session['smugmugRequestToken'] = $requestToken;

				$this->setSuccess(Yii::t('save', 'settings_smugmug_saved'));
				$this->member()->activate();
				$this->refresh();
			}
		}

		if (isset(Yii::app()->session['smugmugRequestToken'])) 
		{
			if (!$this->member()->smugmugSetRequestToken(Yii::app()->session['smugmugRequestToken'])) {
				unset(Yii::app()->session['smugmugRequestToken']);
				$this->refresh();
			}
		}
		
		if (isset($authorize)) {
			try {
				$token = $this->member()->smugmug()->auth_getAccessToken();
			} catch (Exception $e) {
				
				unset(Yii::app()->session['smugmugRequestToken']);
				$this->member()->deleteOption(UserOption::SMUGMUG_HASH);
				$this->member()->deleteOption(UserOption::SMUGMUG_AUTHORIZED);
				$this->member()->deleteOption(UserOption::SMUGMUG_REQUEST);
				$this->setError($e->getMessage());
				$this->redirect(array('settings/smugmug/'));
			}
			
			
			
			
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

		$this->setMemberPageTitle(Yii::t('title', 'smugmug_settings'));

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

		$this->setSuccess(Yii::t('save', 'settings_smugmug_unlinked'));

		$this->redirect(array('settings/smugmug/'));
	}

	public function actionZenfolio()
	{
		$loginForm = new ZenFolioLogin();

		if ($this->member()->zenfolioAuthorized() && $this->member()->zenfolioAuthorize()) 
		{

		} 
		else 
		{
			if (isset($_POST['ZenFolioLogin'])) 
			{
				$loginForm->attributes = $_POST['ZenFolioLogin'];
				if ($loginForm->validate()) 
				{
					try {
						$this->member()->zenfolio()->login("Username=" . $loginForm->username, "Password=" . $loginForm->password); // "Plaintext=TRUE"
						
						$this->member()->editOption(UserOption::ZENFOLIO_HASH, $this->member()->zenfolio()->getAuthToken());
						$this->member()->editOption(UserOption::ZENFOLIO_LOGIN, $loginForm->username);
						
						$this->setSuccess(Yii::t('save', 'settings_zenfolio_authorized'));
						$this->refresh();
					} catch (Exception $e) {
						$loginForm->addError('username', 'Invalid credentials. Please try again.');
					}
				}
			}
		}
		
		$loginForm->username = $this->member()->option(UserOption::ZENFOLIO_LOGIN);

		$this->setMemberPageTitle(Yii::t('title', 'settings_zenfolio'));

		$this->crumb('Settings', array('settings/'))
				->crumb('ZenFolio');

		if ($this->member()->zenfolioAuthorized()) {
			try {
				$this->member()->zenfolioAuthorize();
				$zenfolioProfile = $this->member()->zenfolio()->LoadPrivateProfile();

				$this->member()->activate();

				$this->renderVar('zenfolioProfile', $zenfolioProfile);
			} catch (Exception $e) {
				$this->setError($e->getMessage());
				$this->member()->zenfolioUnauthorize();
				$this->refresh();
			}
			
		}
		
		$this->render('zenfolio', array(
			'zenlogin'	=> $loginForm,
			'entry'		=> $this->member(),
		));
	}
	
	public function actionZenfolioUnlink()
	{
		$this->member()->zenfolioUnauthorize();
		$this->setSuccess(Yii::t('save', 'settings_zenfolio_unlinked'));
		$this->redirect(array('settings/zenfolio/'));
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
			
			$this->setSuccess(Yii::t('save', 'settings_500px_authorized'));
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
		
		$this->setMemberPageTitle(Yii::t('title', 'settings_500px'));
		
		$this->render('500px');
	}
	
	public function action500pxUnlink()
	{
		$this->member()->deleteOption(UserOption::FIVE00_HASH);

		if (isset(Yii::app()->session['500pxRequestToken'])) {
			unset(Yii::app()->session['500pxRequestToken']);
		}
		
		$this->setSuccess(Yii::t('save', 'settings_500px_unlinked'));

		$this->redirect(array('settings/500px/'));
	}
	
	public function actionFacebook()
	{
		$this->setMemberPageTitle(Yii::t('title', 'settings_facebook'));
		
		$this->crumb('Settings', array('settings/'))
				->crumb('Facebook');
		
		$fb_form = new FacebookSettingsForm;
		$fb_form->fb_id = $this->member()->option(UserOption::FACEBOOK_ID, false);
		
		$this->render('facebook', array( 'member' => $this->member(), 'fb' => $fb_form));
	}
	
	public function actionFacebookConnect()
	{
		$options = array(
			'scope' => 'email,publish_stream,offline_access',
			'client_id' => Yii::app()->settings->get('facebook_app_id'),
			'client_secret' => Yii::app()->settings->get('facebook_app_secret')
		);
		$authIdentity = Yii::app()->eauth->getIdentity( 'facebook', $options );

		if ($authIdentity->authenticate()) 
		{
			try
			{
				$this->member()->linkFacebook($authIdentity->getAttribute('id'));
				$this->member()->activate();
				$this->setSuccess(Yii::t('save', 'settings_facebook_linked'));
			}
			catch ( CDbException $e )
			{
				$this->setError($e->getMessage());
			}
		}
		
		// special redirect with closing popup window
		$authIdentity->redirect( $this->createAbsoluteUrl('settings/facebook') );
	}
	
	public function actionFacebookUnlink()
	{
		if (!$this->member()->unlinkFacebook())
			$this->setError('Unable to unlink Facebook account');
		else
			$this->setSuccess(Yii::t('save', 'settings_facebook_unlinked'));
		
		$this->redirect(array('settings/'));
	}
}