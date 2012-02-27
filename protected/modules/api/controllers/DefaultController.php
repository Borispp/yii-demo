<?php
class DefaultController extends YsaApiController
{
	/**
	 * Test controller
	 * Renders ajax-form to check how api is working.
	 * @return void
	 */
	public function actionIndex()
	{
		if ('production' == APPLICATION_ENV) {
			$this->redirect(Yii::app()->homeUrl);
		}
		
		
		$this->render('index', array(
			'applicationList'	=> Application::model()->findAll(),
			'eventList'			=> Event::model()->findAllByAttributes(array(
				'type'	=> array('public','proof'),
				'state'	=> 1
			))
		));
	}
}