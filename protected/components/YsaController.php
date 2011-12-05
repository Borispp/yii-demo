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
    
    public function sendJson($data)
    {
        echo CJSON::encode($data);
        Yii::app()->end();
    }
    
    public function sendJsonSuccess($data = array())
    {
        $this->sendJson(array_merge(array('success' => 1), $data));
    }
    
    public function sendJsonError($data = array())
    {
        $this->sendJson(array_merge(array('error' => 1), $data));
    }
}