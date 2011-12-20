<?php
class MaintenanceController extends YsaFrontController
{
    public $layout = '//layouts/maintenance';

    public function actionIndex()
    {
        $this->render('index');
    }
}