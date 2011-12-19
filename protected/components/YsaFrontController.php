<?php
class YsaFrontController extends YsaController
{
    public $layout='/layouts/general';
    
    public $menu = array();
    
    public function init()
    {
        parent::init();
    }
    
    /**
     * Register web application's resources and meta.
     * @param object $view
     * @return bool
     */
    public function beforeRender($view) 
    {
        parent::beforeRender($view);
        
        $this->setMetaTitle(Yii::app()->settings->get('site_title'));
        
        Yii::app()->getClientScript()
            ->registerMetaTag($this->getMetaDescription(), 'description')
            ->registerMetaTag($this->getMetaKeywords(), 'keywords')
            ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins.js', CClientScript::POS_HEAD)
            ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/screen.js', CClientScript::POS_HEAD)
            ->registerCssFile(Yii::app()->baseUrl . '/resources/css/style.css');
        
        return true;
    }
}