<?php
class EventController extends YsaMemberController
{
    public function actionIndex()
    {
		if (isset($_POST['Fields'])) {
			if (isset ($_POST['SearchBarReset']) && $_POST['SearchBarReset']) {
				Event::model()->resetSearchFields();
			} else {
				Event::model()->setSearchFields($_POST['Fields']);
			}
			$this->redirect(array('event/'));
		}
		
		$criteria = Event::model()->searchCriteria();
        
        $pagination = new CPagination(Event::model()->count($criteria));
        $pagination->pageSize = Yii::app()->params['admin_per_page'];        
        $pagination->applyLimit($criteria);
		
        $entries = Event::model()->findAll($criteria);
        
        $this->render('index',array(
            'entries'       => $entries,
            'pagination'    => $pagination,
            'searchOptions' => Event::model()->searchOptions(),
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
            
            // generate password if not set
            if (!$entry->passwd) {
                $entry->generatePassword();
            }
            
            if ($entry->validate()) {
                $entry->save();
                
                // create default proofing album for proof event
                if (Event::TYPE_PROOF === $entry->type) {
                    $album = new EventAlbum();
                    $album->setAttributes(array(
                        'event_id'  => $entry->id,
                        'name'      => EventAlbum::PROOFING_NAME,
                        'state'     => EventAlbum::STATE_ACTIVE,
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
	
    public function actionEdit($eventId)
    {
		$entry = Event::model()->findByPk($eventId);
		
		if (!$entry) {
			$this->redirect(array('event/'));
		}
		
		if (isset($_POST['Event'])) {
			$entry->attributes = $_POST['Event'];
			
			if (!$entry->passwd) {
				$entry->generatePassword();
			}
			
			if ($entry->validate()) {
                $entry->save();
				
				$this->redirect(array('event/view/' . $entry->id));
			}
		}
		
        $this->render('edit', array(
			'entry' => $entry,
        ));
    }
	
	public function actionDelete($eventId = 0)
	{
        $ids = array();
        if (isset($_POST['ids']) && count($_POST['ids'])) {
            $ids = $_POST['ids'];
        } elseif ($eventId) {
            $ids = array(intval($eventId));
        }
		
        foreach ($ids as $id) {
			Event::model()->deleteByPk($id);
        }
        
        if (Yii::app()->getRequest()->isAjaxRequest) {
            $this->sendJsonSuccess();
        } else {
			$this->redirect(array('event/'));
        }
	}
}