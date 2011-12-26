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
				$this->member()->editOption(UserOption::SMUGMUG_REQUEST, $requestToken);
                $this->setSuccess('SmugMug settings successfully saved.');
				$this->refresh();
            }
		}
		
		$smugRequest = $this->member()->option(UserOption::SMUGMUG_REQUEST);
		$smugHash = $this->member()->option(UserOption::SMUGMUG_HASH);
		$smugAuthorized = $this->member()->option(UserOption::SMUGMUG_AUTHORIZED);
		
		if ($this->member()->option(UserOption::SMUGMUG_REQUEST)) {
			$this->member()->smugmugSetRequestToken();
			
			if (isset($authorize)) {
				try {
					$token = $this->member()->smugmug()->auth_getAccessToken();
					
					// save auth token and remove request token
					$this->member()->editOption(UserOption::SMUGMUG_HASH, $token);
					$this->member()->editOption(UserOption::SMUGMUG_AUTHORIZED, 1);
					$this->member()->deleteOption(UserOption::SMUGMUG_REQUEST);
					
					$this->redirect(array('settings/smugmug/'));
					
				} catch (Exception $e) {
					$this->setError($e->getMessage());
					$this->redirect(array('settings/smugmug/'));
				}	
			} else {
				
			}
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
}