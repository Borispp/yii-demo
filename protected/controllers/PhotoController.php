<?php
class PhotoController extends YsaFrontController
{
	public $layout='/layouts/share';
	
    public function actionView($k = '')
    {
		$entry = EventPhoto::model()->find('basename=:basename', array('basename' => $k));
		
		if (!$entry || !$entry->canShare()) {
			$this->redirect(Yii::app()->homeUrl);
		}
		
		$this->render('view', array(
			'entry' => $entry,
		));
    }
}