<?php
class SmugmugController extends YsaMemberController
{
	/**
	 * Show all album photos 
	 * Available through AJAX only
	 */
	public function actionAlbum()
	{
		if (Yii::app()->getRequest()->isAjaxRequest && $this->member()->smugmugAuthorized()) {
			
			try {
				$this->member()->smugmugSetAccessToken();
				
				list ($smugmugAlbumId, $smugmugAlbumKey) = explode('|', $_POST['smugmug']);

				$images = $this->member()->smugmug()->images_get('AlbumID=' . $smugmugAlbumId, 'AlbumKey=' . $smugmugAlbumKey);

				$album = $this->member()->smugmug()->albums_getInfo('AlbumID=' . $smugmugAlbumId, 'AlbumKey=' . $smugmugAlbumKey);
				
				$this->sendJsonSuccess(array(	
					'html' => $this->renderPartial('_album', array('images' => $images, 'album' => $album), true),
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