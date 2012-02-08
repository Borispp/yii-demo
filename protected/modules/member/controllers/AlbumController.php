<?php
class AlbumController extends YsaMemberController
{
	public function init() {
		parent::init();
		
		$this->crumb('Events', array('event/'));
	}
	
	public function actionCreate($event)
	{
		$event = Event::model()->findByIdLogged($event);
		
		// no event found or it's a proofing event
		if (!$event || Event::TYPE_PROOF == $event->type) {
			$this->redirect(array('event/'));
		}
		
		$entry = new EventAlbum();
		$entry->event_id = $event->id;
		
		if (isset($_POST['EventAlbum'])) {
			$entry->attributes = $_POST['EventAlbum'];
			
			if ($entry->validate()) {
				
				if ($entry->shooting_date) {
					$entry->shooting_date = YsaHelpers::formatDate($entry->shooting_date, Event::FORMAT_DATE);
				}
				
				$entry->save();
				$this->redirect(array('album/view/' . $entry->id));
			}
		}
		
		$this->crumb($event->name, array('event/view/' . $event->id))
			 ->crumb('Create Album');
		
		$this->setMemberPageTitle(Yii::t('title', 'album_save'));
		
		$this->render('create', array(
			'event' => $event,
			'entry' => $entry,
		));
	}
	
	public function actionEdit($albumId)
	{
		$entry = EventAlbum::model()->findByPk($albumId);
		
		if (!$entry || !$entry->event) {
			$this->redirect(array('event/'));
		}
		
		if (isset($_POST['EventAlbum'])) {
			$entry->attributes = $_POST['EventAlbum'];
			$entry->shooting_date = YsaHelpers::formatDate($entry->shooting_date, Event::FORMAT_DATETIME);
			if ($entry->validate()) {
				$entry->save();
				$this->redirect(array('album/view/' . $entry->id));
			}
		}
		
		$this->crumb($entry->event->name, array('event/view/' . $entry->event->id))
			 ->crumb($entry->name, array('album/view/' . $entry->id))
			 ->crumb('Edit Album');
		
		$this->setMemberPageTitle(Yii::t('title', 'album_edit'));
		
		$this->render('edit', array(
			'entry' => $entry,
		));
	}
	
	public function actionView($albumId)
	{
		$entry = EventAlbum::model()->findByPk($albumId);
		
		if (!$entry || !$entry->event || !$entry->event->isOwner()) {
			$this->redirect(array('event/'));
		}
		$upload = new PhotoUploadForm();
		$photoSizes = PhotoSize::model()->findActive();
		$availability = new AlbumPhotoAvailability();
		
		$availability->can_save =$entry->canSave();
//		$availability->can_order = $entry->canOrder();
		$availability->can_share = $entry->canShare();
		$availability->order_link = $entry->order_link;
		
		if (isset($_POST['PhotoUploadForm'])) {
			$upload->photo = CUploadedFile::getInstance($upload, 'photo');
			if ($upload->validate()) {
				$photo = new EventPhoto();
				$photo->album_id = $entry->id;
				$photo->upload($upload->photo);
				
				$this->refresh();
			}
		}
		
//		if (isset($_POST['AlbumSizes']) && count($_POST['AlbumSizes']) && is_array($_POST['AlbumSizes'])) {
//			$entry->setSizes($_POST['AlbumSizes']);
//			$this->refresh();
//		}		
//		if (isset($_POST['AlbumPhotoAvailability']) && !$entry->event->isProofing()) {
//			
//			$availability->attributes = $_POST['AlbumPhotoAvailability'];
//			
//			if ($availability->validate()) {
//				$entry->can_order = $availability->can_order;
//				$entry->can_share = $availability->can_share;
//				$entry->save();
//			}
//			$this->refresh();
//		}
		
		try {
			if ($this->member()->smugmugAuthorized()) {
				$this->member()->smugmugSetAccessToken();
			}
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
		
		try {
			if ($this->member()->zenfolioAuthorized()) {
				$this->member()->zenfolioAuthorize();
				$profile = $this->member()->zenfolio()->LoadPrivateProfile();
				$hierarchy = $this->member()->zenfolio()->LoadGroupHierarchy($profile['LoginName']);
				
				$this->renderVar('zenfolioHierarchy', $hierarchy);
			}
		} catch (Exception $e) {
			$this->member()->zenfolioUnauthorize();
			$this->setError($e->getMessage());
		}

		
		$this->loadPlupload(true);
		
		$this->crumb($entry->event->name, array('event/view/' . $entry->event->id))
			 ->crumb($entry->name);
		
		$this->setMemberPageTitle($entry->name);
		
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/albumpage.js', CClientScript::POS_HEAD);
		
		$this->render('view', array(
			'entry'			=> $entry,
			'upload'		=> $upload, 
			'photoSizes'	=> $photoSizes,
			'availability'	=> $availability,
		));
	}
	
	public function actionDelete($albumId = 0)
	{
		$ids = array();
		if (isset($_POST['ids']) && count($_POST['ids'])) {
			$ids = $_POST['ids'];
		} elseif ($albumId) {
			$ids = array(intval($albumId));
		}
		
		foreach ($ids as $id) {
			$album = EventAlbum::model()->findByPk($id);
			if ($album) {
				$event = $album->event;
				if ($event->isOwner()) {
					$album->delete();
				}
			}
		}
		
		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			if (isset($event)) {
				$this->redirect(array('event/view/' . $event->id));
			} else {
				$this->redirect(array('event/'));
			}
		}
	}
	
	public function actionSort()
	{
		if (Yii::app()->getRequest()->isAjaxRequest) {
			if (isset($_POST['event-album'])) {
				foreach ($_POST['event-album'] as $k => $id) {
					$entry = EventAlbum::model()->findByPk($id);
					if ($entry && $entry->event->isOwner()) {
						$entry->rank = $k + 1;
						$entry->save();
					}
				}
			}
			$this->sendJsonSuccess();
		} else {
			$this->redirect(Yii::app()->homeUrl);
		}
	}
	
	public function actionToggle($albumId = 0)
	{
		if (Yii::app()->getRequest()->isAjaxRequest) {
			$entry = EventAlbum::model()->findByPk($albumId);
			if ($entry && $entry->event->isOwner()) {
				if (isset($_POST['state']) && in_array($_POST['state'], array_keys(EventAlbum::model()->getStates()))) {
					$entry->state = intval($_POST['state']);
					$entry->save();
					$this->sendJsonSuccess();
				} else {
					$this->sendJsonError(array(
						'msg' => Yii::t('error', 'standart_error'),
					));
				}
			}
			
		} else {
			$this->redirect(Yii::app()->homeUrl);
		}
	}
	
	public function actionSaveSizes($albumId)
	{
		if (isset($_POST['AlbumSizes']) && count($_POST['AlbumSizes']) && is_array($_POST['AlbumSizes'])) {
			$entry = EventAlbum::model()->findByPk($albumId);
			
			if (!$entry || !$entry->event->isOwner()) {
				$this->redirect(array('event/'));
			}
			
			// set order sizes
			$entry->setSizes($_POST['AlbumSizes']);
			
			if (Yii::app()->request->isAjaxRequest) {
				$this->sendJsonSuccess();
			} else {
				$this->redirect(array('album/view/' . $entry->id));
			}
		} else {
			$this->redirect(array('event/'));
		}
	}
	
	public function actionSaveAvailability($albumId)
	{
		if (isset($_POST['AlbumPhotoAvailability'])) {
			
			$entry = EventAlbum::model()->findByPk($albumId);
			
			if (!$entry || !$entry->event->isOwner() || $entry->event->isProofing()) {
				$this->redirect(array('event/'));
			}
			
			$availability = new AlbumPhotoAvailability();
			
			$availability->attributes = $_POST['AlbumPhotoAvailability'];
			
			if ($availability->validate()) {
				$entry->order_link = $availability->order_link;
				$entry->can_order = $availability->can_order;
				$entry->can_share = $availability->can_share;
				$entry->save();
			}
			
			if (Yii::app()->request->isAjaxRequest) {
				$this->sendJsonSuccess();
			} else {
				$this->redirect(array('album/view/' . $entry->id));
			}
		}
	}
	
	public function actionSetCover($albumId = 0)
	{
		$entry = EventAlbum::model()->findByPk($albumId);
		
		$photo = EventPhoto::model()->findByPk($_POST['photo']);
		
		if ($entry && $photo && $entry->id == $photo->album_id && $entry->isOwner()) {
			$entry->cover_id = $photo->id;
			$entry->save();
		}
		
		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(array('album/' . $entry->id));
		}
	}
	
}