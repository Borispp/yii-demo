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
        
        $album = new EventAlbum();
        $album->event_id = $event->id;
        
        if ($_POST['EventAlbum']) {
            VarDumper::dump($_POST);
        }
        
        $this->render('create', array(
            'event' => $event,
        ));
        
    }
}