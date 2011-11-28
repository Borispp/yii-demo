<?php

class DefaultController extends YsaAdminController
{
    public function actionIndex()
    {
        $this->render('index');
    }
}