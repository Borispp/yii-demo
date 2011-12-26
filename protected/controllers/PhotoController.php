<?php
class PhotoController extends YsaFrontController
{
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionView($k = '')
    {	
        
		$entry = EventPhoto::model()->find('basename=:basename', array('basename' => $k));
		
		if (!$entry || $entry->album()->event()->type == Event::TYPE_PROOF) {
			$this->redirect(Yii::app()->homeUrl);
		}
		
		$this->render('view', array(
			'entry' => $entry,
		));
		
    }
}