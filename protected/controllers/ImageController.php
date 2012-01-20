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
		header('Content-Type: ' . $obEventPhoto->meta_type);
		header("Accept-Ranges: bytes");
		header("Content-Disposition: Inline; filename=".basename($fname));
		header("Content-Length: ".$obEventPhoto->size);
		readfile($fname);
		die;
	}

	public function actionThumb($imageId)
	{
		if (!($obEventPhoto = EventPhoto::model()->findByKey($imageId)))
			throw new CHttpException(404,'The requested image does not exist.');
		
		$fname = ImageHelper::thumbPath($_GET['width'], $_GET['height'], $obEventPhoto->path());
		header("HTTP/1.1 200 OK");
		header("Connection: close");
		header ('Content-Type: ' . $obEventPhoto->meta_type);
		header("Accept-Ranges: bytes");
		header("Content-Disposition: Inline; filename=".basename($fname));
		header("Content-Length: ".filesize($fname));
		readfile($fname);
		die;
	}
}