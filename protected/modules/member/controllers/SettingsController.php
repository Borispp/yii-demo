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
        }
		
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
		
		$this->setMemberPageTitle('Settings');
		
        $this->render('index', array(
            'entry'     => $this->member(),
            'password'  => $changePasswordForm,
			'shootq'	=> $shootqForm,
        ));
    }
}