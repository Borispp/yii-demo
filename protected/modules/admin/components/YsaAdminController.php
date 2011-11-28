<?php
class YsaAdminController extends YsaController
{
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

    public function setNotice($message)
    {
        return Yii::app()->user->setFlash('notice', $message);
    }

    public function setError($message)
    {
        return Yii::app()->user->setFlash('error', $message);
    }
}