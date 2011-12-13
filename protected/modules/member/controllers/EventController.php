<?php
class EventController extends YsaMemberController
{
    public function actionIndex()
    {
        $this->render('index', array(
            
        ));
    }
    
    public function actionView($eventId)
    {
        $entry = Event::model()->findByPk($eventId);
        
        if (!$entry) {
            $this->redirect(array('event/'));
        }
        
        $this->render('view', array(
            'entry' => $entry,
        ));
    }
    
    public function actionCreate()
    {
        $entry = new Event();
        
        if (isset($_POST['Event'])) {
            
            $entry->attributes = $_POST['Event'];
            
            $entry->user_id = $this->member()->id;
            
            if ($entry->validate()) {
                $entry->save();
                
                // create default proofing album for proof event
                if (Event::TYPE_PROOF === $entry->type) {
                    $album = new EventAlbum();
                    $album->setAttributes(array(
                        'event_id'  => $entry->id,
                        'name'      => EventAlbum::PROOFING_NAME,
                    ));
                    $album->save();
                }
                
                
                $this->redirect(array('event/view/' . $entry->id));
            }
        }
        
        $this->render('create', array(
            'entry' => $entry,
        ));
    }
    
    public function actionEdit()
    {
        $this->render('edit', array(
            
        ));
    }
}