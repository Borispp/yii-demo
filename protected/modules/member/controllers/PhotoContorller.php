<?php
class PhotoController extends YsaMemberController
{
    public function actionUpload($albumId)
    {
		
    }
	
	public function actionView($photoId)
	{
		
	}
    
	public function actionDelete($photoId = 0)
	{
		VarDumper::dump('delete');
	}
}