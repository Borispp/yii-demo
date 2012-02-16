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
	
	/**
	 * Import ZenFolio Album 
	 * Available through AJAX only
	 */
	public function actionImportAlbum()
	{
		if (Yii::app()->getRequest()->isAjaxRequest && $this->member()->zenfolioAuthorized()) {
			
			try {
				$this->member()->zenfolioAuthorize();

				if (!isset($_POST['id']) || !isset($_POST['event_id'])) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}
				
				$event = Event::model()->findByPk($_POST['event_id']);
				if (!$event) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}
				
				$albumId = (int) $_POST['id'];
				$photoSet = $this->member()->zenfolio()->LoadPhotoSet($albumId, 'Full', true);
				
				if (!$photoSet) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}
				
				if (!$photoSet['PhotoCount']) {
					throw new Exception(Yii::t('error', 'import_album_empty'));
				}
				
				$album = new EventAlbum();
				$album->event_id = $event->id;
				$album->importZenfolioAlbum($photoSet);
				
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
		if (Yii::app()->getRequest()->isAjaxRequest && isset($_POST['id']) && isset($_POST['album_id'])) {
			try {
				$album = EventAlbum::model()->findByPk($_POST['album_id']);
				
				if (!$album || !$album->isOwner()) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}
				
				$this->member()->zenfolioAuthorize();
				$zenfolioPhotoId = (int) $_POST['id'];
								
				$image = $this->member()->zenfolio()->LoadPhoto($zenfolioPhotoId, 'Full');
				
				if (!$image) {
					throw new Exception(Yii::t('error', 'standart_error'));
				}

				$entry = new EventPhoto();
				$entry->album_id = $album->id;
				$entry->import($image, 'zenfolio');
				
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