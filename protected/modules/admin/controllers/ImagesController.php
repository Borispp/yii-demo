<?php
class ImagesController extends YsaAdminController
{
	public function actionConnector()
	{
		$opts = array(
			'root'        => Yii::getPathOfAlias('webroot.images.uploads'),
			'URL'         => Yii::app()->getBaseUrl(true) . '/images/uploads/',
			'rootAlias'   => 'Images',
			'uploadAllow' => array('image'),
		);

		$fm = new elFinder($opts);
		$fm->run();

		// force ending application flow
		Yii::app()->end();
	}
}