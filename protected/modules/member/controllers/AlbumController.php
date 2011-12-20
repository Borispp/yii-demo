<?php
class AlbumController extends YsaMemberController
{
	public function init() {
		parent::init();
		
		$this->crumb('Events', array('event/'));
	}


	public function actionCreate($event)
    {
        $event = Event::model()->findByPk($event);
        
        // no event found or it's a proofing event
        if (!$event || Event::TYPE_PROOF == $event->type) {
            $this->redirect(array('event/'));
        }
        
        $entry = new EventAlbum();
        $entry->event_id = $event->id;
		
        if (isset($_POST['EventAlbum'])) {
            $entry->attributes = $_POST['EventAlbum'];
            
            if ($entry->validate()) {
                $entry->save();
                $this->redirect(array('album/view/' . $entry->id));
            }
        }
		
		$this->crumb($event->name, array('event/view/' . $event->id))
			 ->crumb('Create Album');
        
        $this->render('create', array(
            'event' => $event,
            'entry' => $entry,
        ));
    }
    
	public function actionEdit($albumId)
	{
		$entry = EventAlbum::model()->findByPk($albumId);
		
		if (!$entry || !$entry->event()) {
            $this->redirect(array('event/'));
        }
		
		if (isset($_POST['EventAlbum'])) {
            $entry->attributes = $_POST['EventAlbum'];
            
            if ($entry->validate()) {
                $entry->save();
                $this->redirect(array('album/view/' . $entry->id));
            }
		}
		
		$this->crumb($entry->event()->name, array('event/view/' . $entry->event()->id))
			 ->crumb('Edit Album');
		
		
		
        $this->render('edit', array(
            'entry' => $entry,
        ));
	}
	
    public function actionView($albumId)
    {
        $entry = EventAlbum::model()->findByPk($albumId);
        
        if (!$entry || !$entry->event()) {
            $this->redirect(array('event/'));
        }
		$upload = new PhotoUploadForm();
		
		if (Yii::app()->getRequest()->isPostRequest) {
			$upload->photo = CUploadedFile::getInstance($upload, 'photo');
			if ($upload->validate()) {
				
				$photo = new EventPhoto();
				$photo->album_id = $entry->id;
				
				$photo->upload($upload->photo);
				
				$this->refresh();
			}
		}
        
		$this->loadSwfUploader();
		
		$this->crumb($entry->event()->name, array('event/view/' . $entry->event()->id))
			 ->crumb($entry->name);
		
        $this->render('view', array(
            'entry'   => $entry,
			'upload'  => $upload, 
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
				$event = $album->event();
				$album->delete();	
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
	
	public function actionSort($albumId = 0)
	{
		if (Yii::app()->getRequest()->isAjaxRequest) {
			if (isset($_POST['event-album'])) {
				foreach ($_POST['event-album'] as $k => $id) {
					$entry = EventAlbum::model()->findByPk($id);
					if ($entry) {
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
}