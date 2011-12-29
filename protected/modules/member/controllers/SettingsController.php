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
		$this->render('500px', array(
			
		));
	}
}