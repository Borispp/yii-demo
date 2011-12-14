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
}