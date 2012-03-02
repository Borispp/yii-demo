<?php
class PassController extends YsaMemberController
{
	/**
	 * Available through AJAX only
	 */
	public function actionImportAlbum()
	{
		$pass_api = $this->member()->passApi();
		if (!Yii::app()->getRequest()->isAjaxRequest || !$this->member()->passApiLinked())
			Yii::app()->end();
			
		try 
		{
			if (!isset($_POST['id']) || !isset($_POST['event_id'])) {
				throw new Exception(Yii::t('error', 'standart_error'));
			}
			
			$event = Event::model()->findByPk($_POST['event_id']);
			if (!$event) {
				throw new Exception(Yii::t('error', 'standart_error'));
			}

			list($event_id, $collection_id) = explode('|', $_POST['id']);
			$photoSet = $pass_api->loadPhotoSet($event_id, $collection_id, PassApi::PHOTO_SIZE_LARGE);

			if (!$photoSet) {
				throw new Exception(Yii::t('error', 'standart_error'));
			}

			if (!$photoSet['PhotoCount']) {
				throw new Exception(Yii::t('error', 'import_album_empty'));
			}

			$album = new EventAlbum();
			$album->event_id = $event->id;
			$album->importPassAlbum($photoSet);

			$this->sendJsonSuccess(array(	
				'html' => $this->renderPartial('/album/_listalbum', array('album' => $album, 'event' => $event), true),
				'msg' => Yii::t('api', 'service_event_album_imported'),
			));

		} catch (Exception $e) {
			$this->sendJsonError(array(
				'msg' => $e->getMessage(),
			));
		}
		
		Yii::app()->end();
	}
	
	/**
	 * Available through AJAX only
	 */
	public function actionImportPhoto()
	{
		if (!Yii::app()->getRequest()->isAjaxRequest or !isset($_POST['pass']) or !isset($_POST['album_id']))
			Yii::app()->end();
			
		try 
		{
			$album = EventAlbum::model()->findByPk($_POST['album_id']);

			if (!$album || !$album->isOwner()) {
				throw new Exception(Yii::t('error', 'standart_error'));
			}

			$img_data = unserialize(urldecode($_POST['pass']));
			if (!$img_data) {
				throw new Exception(Yii::t('error', 'standart_error'));
			}

			$img_data['URL'] = PassApi::changeImageSizeInUrl($img_data['URL'], PassApi::PHOTO_SIZE_LARGE);
			
			$entry = new EventPhoto();
			$entry->album_id = $album->id;
			$entry->import($img_data, 'pass');

			$this->sendJsonSuccess(array(
				'html' => $this->renderPartial('/photo/_listphoto', array('entry' => $entry), true),
			));

		} catch (Exception $e) 
		{
			$this->sendJsonError(array(
				'msg' => $e->getMessage(),
			));
		}

		Yii::app()->end();
	}
	
	/**
	 * Show all album photos 
	 * Available through AJAX only
	 */
	public function actionAlbum()
	{
		if (!Yii::app()->getRequest()->isAjaxRequest || !$this->member()->passApiLinked())
			Yii::app()->end();
		
		try 
		{
			list ($event_id, $collection_id) = explode('|', $_POST['pass']);
			$images = $this->member()->passApi()->loadPhotoSet($event_id, $collection_id, PassApi::PHOTO_SIZE_THUMBNAIL);

			if (!$images) {
				throw new Exception(Yii::t('error', 'standart_error'));
			}

			$album = array();//$this->member()->smugmug()->albums_getInfo('AlbumID=' . $smugmugAlbumId, 'AlbumKey=' . $smugmugAlbumKey);

			$this->sendJsonSuccess(array(	
				'html' => $this->renderPartial('_album', array('images' => $images, 'album' => $album), true),
			));

		} catch (Exception $e) {
			$this->sendJsonError(array(
				'msg' => $e->getMessage(),
			));
		}
		
		Yii::app()->end();
	}
}