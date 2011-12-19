<?php
class SettingsChangePassword extends YsaFormModel 
{
    public $currentPassword;
    
    public $newPassword;

    public $repeatPassword;
    
    public function rules() 
    {
        return array(
            array('currentPassword, newPassword, repeatPassword', 'required'),
            array('currentPassword', 'compareWithCurrentPassword'),
            array('newPassword', 'length', 'max'=>128, 'min' => 4, 'message' => "Incorrect password (minimal length 4 symbols)."),
            array('newPassword', 'compare', 'compareAttribute'=>'repeatPassword', 'message' => "Retype Password is incorrect."),
        );
    }

    public function attributeLabels()
    {
        return array(
            'currentPassword'   => 'Current Password',
            'newPassword'       => 'New Password',
            'repeatPassword'    => 'Old Password',
        );
    }
    
    public function compareWithCurrentPassword($attribute,$params)
    {
        $passwd = YsaHelpers::encrypt($this->$attribute);
        if ($passwd !== Yii::app()->controller->member()->password) {
            $this->addError($attribute, 'Incorrect old password.');
        }
        return true;
    }
}