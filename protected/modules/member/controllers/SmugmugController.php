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
				
				if (!$images) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}
				
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
	
	/**
	 * Import ZenFolio Album 
	 * Available through AJAX only
	 */
	public function actionImportAlbum()
	{
		if (Yii::app()->getRequest()->isAjaxRequest && $this->member()->smugmugAuthorized()) {
			
			try {
				$this->member()->smugmugSetAccessToken();

				if (!isset($_POST['id']) || !isset($_POST['event_id'])) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}
				
				$event = Event::model()->findByPk($_POST['event_id']);
				if (!$event) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}
				
				list ($smugmugAlbumId, $smugmugAlbumKey) = explode('|', $_POST['id']);

				$photoSet = $this->member()->smugmug()->albums_getInfo('AlbumID=' . $smugmugAlbumId, 'AlbumKey=' . $smugmugAlbumKey);
				
				if (!$photoSet) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}
				
				if (!$photoSet['ImageCount']) {
					throw new Exception(Yii::t('error', 'import_album_empty'));
				}
				
				$photoSet['photoSetImages'] = $this->member()->smugmug()->images_get('AlbumID=' . $smugmugAlbumId, 'AlbumKey=' . $smugmugAlbumKey, 'Heavy=true', 'Extras=EXIF');
				
				$album = new EventAlbum();
				$album->event_id = $event->id;
				$album->importSmugmugAlbum($photoSet);
				
				$this->sendJsonSuccess(array(	
					'html' => $this->renderPartial('/album/_listalbum', array('album' => $album, 'event' => $event), true),
					'msg' => Yii::t('api', 'service_event_album_imported'),
				));
				
			} catch (Exception $e) {
				$this->sendJsonError(array(
					'msg' => $e->getMessage(),
				));
			}
		}
		
		Yii::app()->end();
	}
	
	public function actionImportPhoto()
	{
		if (Yii::app()->getRequest()->isAjaxRequest && isset($_POST['smugmug']) && isset($_POST['album_id'])) {
			
			try {
				$this->member()->smugmugSetAccessToken();
				
				$album = EventAlbum::model()->findByPk($_POST['album_id']);
				
				if (!$album || !$album->isOwner()) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}
				
				list ($smugmugPhotoId, $smugmugPhotoKey) = explode('|', $_POST['smugmug']);
				
				$image = $this->member()->smugmug()->images_getInfo('ImageID=' . $smugmugPhotoId, 'ImageKey=' . $smugmugPhotoKey, 'Extras=EXIF');
				
				if (!$image) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}
				
				$image['EXIF'] = $this->member()->smugmug()->images_getEXIF('ImageID=' . $smugmugPhotoId, 'ImageKey=' . $smugmugPhotoKey);
				
				$entry = new EventPhoto();
				$entry->album_id = $album->id;
				$entry->import($image, 'smugmug');
				
				$this->sendJsonSuccess(array(
					'html' => $this->renderPartial('/photo/_listphoto', array('entry' => $entry), true),
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