<?php
class YsaController extends CController
{
    public $layout='//layouts/column1';
    
    public $menu=array();
    
    public $breadcrumbs=array();
    
    public function setNotice($message)
    {
        return Yii::app()->user->setFlash('notice', $message);
    }

    public function setError($message)
    {
        return Yii::app()->user->setFlash('error', $message);
    }
}