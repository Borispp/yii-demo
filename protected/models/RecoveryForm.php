<?php
class RecoveryForm extends CFormModel 
{
	public $email;
        
        public $user_id;
	
	public function rules()
	{
            return array(
                array('email', 'required'),
                array('email', 'email'),
                array('email', 'checkexists'),
            );
	}
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
            return array(
            'email' => 'Email',
            );
	}
	
	public function checkexists($attribute, $params) {
            if(!$this->hasErrors()) {
                $user=User::model()->findByAttributes(array('email' => $this->email));
                if ($user) {
                    $this->user_id = $user->id;
                } else {
                    $this->addError("email", 'Email is incorrect');
                }
            }
	}
	
}