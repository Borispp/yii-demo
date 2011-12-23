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

        $this->render('index', array(
            'entry'     => $this->member(),
            'password'  => $changePasswordForm,
        ));
    }
}