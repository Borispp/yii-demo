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
		$this->render('index', array(
			'applicationList'	=> Application::model()->findAll(),
			'eventList'			=> Event::model()->findAllByAttributes(array(
				'type'	=> array('portfolio','proof'),
				'state'	=> 1
			))
		));
	}
}