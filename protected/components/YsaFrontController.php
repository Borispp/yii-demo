<?php
class YsaFrontController extends YsaController
{
    public $layout='//layouts/column1';
    
    public $menu = array();
    
    public $breadcrumbs = array();
    
    public function init() 
    {
        parent::init();
        
        $this->setMetaTitle(Yii::app()->settings->get('site_title'));
    }
    
    public function beforeRender($view) {
        parent::beforeRender($view);
        Yii::app()->clientScript->registerMetaTag($this->getMetaDescription(), 'description');
        Yii::app()->clientScript->registerMetaTag($this->getMetaKeywords(), 'keywords');
        
        return true;
    }
}