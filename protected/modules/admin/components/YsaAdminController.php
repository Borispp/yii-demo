<?php
class YsaAdminController extends YsaController
{
    public $layout='main';
    
    protected $_contentTitle;
    
    protected $_contentDescription;
    
    public function accessRules()
    {
        return array(
            array('allow', 'roles' => array('admin')),
            array('deny',  'users' => array('*')),
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function setContentTitle($value, $link = false) {
        
        $this->_contentTitle = $value;
        if (isset ($link[0]) && isset ($link[1])) {
            $this->_contentTitle .= '&nbsp;' . CHtml::openTag('span') . Chtml::link($link[0], $link[1]) . CHtml::closeTag('span');
        }
    }
    
    public function getContentTitle()
    {
        return $this->_contentTitle;
    }
    
    public function hasContentTitle()
    {
        return $this->_contentTitle ? true : false;
    }
    
    public function setContentDescription($value) {
        
        $this->_contentDescription = $value;
    }
    
    public function getContentDescription()
    {
        return $this->_contentDescription;
    }
    
    public function hasContentDescription()
    {
        return $this->_contentDescription ? true : false;
    }
    
    public function getNavigationClass($controller, $action = false)
    {
        $controllers = explode(',', $controller);
		
        if (in_array($this->getId(), $controllers)) {
            if ($action) {
                return $action == $this->getAction()->getId() ? 'active' : '';
            } else {
                return 'active';
            }
        } else {
            return '';
        }
    }
    
    public function setSuccessFlash($msg)
    {
        Yii::app()->user->setFlash('entrySaveSuccess', $msg);
    }
}