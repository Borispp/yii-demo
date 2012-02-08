<?php
class MaintenanceController extends YsaFrontController
{
    public $layout = '//layouts/maintenance';

    public function actionIndex()
    {
		if (!Yii::app()->maintenance->enabled()) {
			$this->redirect(Yii::app()->homeUrl);
		}
		
		Yii::app()->maintenance->setMessage('Sorry for inconvenience. We will be back shortly.');
		
        $this->render('index');
    }
}