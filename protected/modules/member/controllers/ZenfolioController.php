<?php
class ZenfolioController extends YsaMemberController
{
	/**
	 * Show all album photos 
	 * Available through AJAX only
	 */
	public function actionAlbum()
	{
		if (Yii::app()->getRequest()->isAjaxRequest && $this->member()->zenfolioAuthorized()) {
			
			try {
				$this->member()->zenfolioAuthorize();

				if (!isset($_POST['id'])) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}
				
				$albumId = (int) $_POST['id'];
				
				$images = $this->member()->zenfolio()->LoadPhotoSetPhotos($albumId, 0, 100 );
				
				$this->sendJsonSuccess(array(	
					'html' => $this->renderPartial('_album', array('images' => $images), true),
				));
				
			} catch (Exception $e) {
				$this->sendJsonError(array(
					'msg' => $e->getMessage(),
				));
			}
		}
		
		Yii::app()->end();
	}
}