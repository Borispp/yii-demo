<?php
class YsaMemberController extends YsaController
{
    public function accessRules()
    {
        return array(
            array('allow', 'roles' => array('member')),
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
}