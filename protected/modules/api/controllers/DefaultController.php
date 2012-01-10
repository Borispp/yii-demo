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
		$eventResult = Event::model()->findByAttributes(array(
			'type'	=> array('portfolio','proof'),
			'state'	=> 1
		));
		$this->render('index', array(
			'applicationList'	=> Application::model()->findAll(),
			'eventList'			=> is_array($eventResult) ? $eventResult : array($eventResult)
		));
	}
}