<?php
class TutorialController extends YsaFrontController
{
	public function actionDownload($basename)
	{
		$file = TutorialFile::model()->findBy('basename', $basename);
		
		if ($file) {
			Yii::app()->request->sendFile(trim($file->name) . '.' . $file->ext, file_get_contents($file->file()), $file->mime);
			Yii::app()->end();
		}
	}
}