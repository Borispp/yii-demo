<?php
class YsaMaintenance extends CComponent 
{
    protected $_enabled;
    
    protected $_message;

    public function init() 
    {
        $this->_enabled = (bool) Yii::app()->settings->get('maintenance');
        
        if ($this->_enabled) {
            
            $this->_message = Yii::app()->settings->get('maintenance_message');
            
            $disabled = false;
            if (Yii::app()->user->isAdmin()) {
                $disabled = true;
            } else if (in_array(Yii::app()->request->getPathInfo(), array('login', 'logout'))) {
                $disabled = true;
            }
            
            if (!$disabled) {
                Yii::app()->catchAllRequest = array('maintenance/index');
            }
        }
    }
    
    public function getMessage()
    {
        return $this->_message;
    }
}
