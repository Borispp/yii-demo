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
		$this->render('index');
	}
}