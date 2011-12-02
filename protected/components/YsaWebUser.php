<?php
class YsaWebUser extends CWebUser 
{
    private $_model;
    
    public function login($identity, $duration = 0) {
        parent::login($identity, $duration);
    }


    public function isAdmin() 
    {
        $user = $this->loadUser(Yii::app()->user->id);
        return 'admin' == $user->role;
    }
    
    public function isMember() 
    {
        $user = $this->loadUser(Yii::app()->user->id);
        return 'member' == $user->role;
    }
    
    public function isLoggedIn()
    {
        return Yii::app()->user->id ? true : false;
    }

    // Load user model.
    protected function loadUser($id=null) 
    {
        if (null === $this->_model) {
            if (null !== $id) {
                $this->_model = User::model()->findByPk($id);
            }
        }
        return $this->_model;
    }
    
    public function getRole() 
    {
        $user = $this->getModel();
        if($user) {
            // в таблице User есть поле role
            return $user->role;
        }
    }
 
    private function getModel()
    {
        if (!$this->isGuest && $this->_model === null) {
            $this->_model = User::model()->findByPk($this->id, array('select' => 'role'));
        }
        return $this->_model;
    }
}