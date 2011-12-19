<?php
class AlbumController extends YsaMemberController
{
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
        
        $this->render('view', array(
            'entry' => $entry,
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
	
	public function actionSortPhotos()
	{
		VarDumper::dump('sorting photos');
	}
}