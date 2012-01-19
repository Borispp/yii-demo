<?php
class ImageController extends YsaFrontController
{
	public function actionGet($imageId)
	{
		if (!($obEventPhoto = EventPhoto::model()->findByKey($imageId)))
			throw new CHttpException(404,'The requested image does not exist.');
		$fname = $obEventPhoto->path();
		header("HTTP/1.1 200 OK");
		header("Connection: close");
		header ('Content-Type: ' . mime_content_type($fname));
		header("Accept-Ranges: bytes");
		header("Content-Disposition: Attachment; filename=".basename($fname));
		header("Content-Length: ".filesize($fname));
		readfile($fname);
		die;
	}
}