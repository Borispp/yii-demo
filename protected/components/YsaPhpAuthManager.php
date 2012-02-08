<?php
class YsaPhpAuthManager extends CPhpAuthManager
{
    public function init()
    {
        if($this->authFile === null){
            $this->authFile = Yii::getPathOfAlias('application.config.auth').'.php';
        }
 
        parent::init();
        
        if(!Yii::app()->user->isGuest) 
		{
			if (Yii::app()->user->isInteresant())
				return $this->assign('interesant', Yii::app()->user->id);
			
            $this->assign(Yii::app()->user->role, Yii::app()->user->id);
        }
    }
}