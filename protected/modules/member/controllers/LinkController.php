<?php
class LinkController extends YsaMemberController
{
	protected $_type;
	
	public function actionAddCustom()
	{
		$this->_type = StudioLink::TYPE_CUSTOM;
		
		$this->forward('add');
	}
	
	public function actionAddBookmark()
	{
		$this->_type = StudioLink::TYPE_BOOKMARK;
		
		$this->forward('add');
	}
	
	public function actionEditCustom()
	{
		$this->_type = StudioLink::TYPE_CUSTOM;
		
		$this->forward('edit');
	}
	
	public function actionEditBookmark()
	{
		$this->_type = StudioLink::TYPE_BOOKMARK;
		
		$this->forward('edit');
	}
	
	public function actionDeleteCustom()
	{
		$this->_type = StudioLink::TYPE_CUSTOM;
		
		$this->forward('delete');
	}
	
	public function actionDeleteBookmark()
	{
		$this->_type = StudioLink::TYPE_BOOKMARK;
		
		$this->forward('delete');
	}
	
	public function actionSortCustom()
	{
		$this->_type = StudioLink::TYPE_CUSTOM;
		
		$this->forward('sort');
	}
	
	public function actionSortBookmark()
	{
		$this->_type = StudioLink::TYPE_BOOKMARK;
		
		$this->forward('sort');
	}
	
    public function actionAdd()
    {
		$entry = new StudioLink();
		
		if (!in_array($this->_type, array_keys(StudioLink::model()->getTypes()))) {
			$this->redirect(array('studio/'));
//			$this->setError('Cannot find the right type.');
		}
		
		if (isset($_POST['StudioLink'])) {
			
			$entry->attributes = $_POST['StudioLink'];
			$entry->studio_id = $this->member()->studio->id;
			$entry->type = $this->_type;
			
			if ($this->_type == StudioLink::TYPE_BOOKMARK) {
				$entry->icon = '';
			}
			
			$entry->setNextRank();
			
			if ($entry->validate()) {
				$entry->save();
				$this->setSuccess('Link was successfully added.');
				
				$this->redirect(array('studio/'));
			}
		}
		
		if (Yii::app()->request->isAjaxRequest || isset($_GET['iframe'])) {
			$this->renderPartial('add', array(
				'entry' => $entry,
				'type'	=> $this->_type,
			));
			Yii::app()->end();
		}
		
		$this->setMemberPageTitle('Add Link');
		
		$this->crumb('Studio', array('studio/'))
			 ->crumb('Add Link');
		
		$this->render('add', array(
			'entry' => $entry,
			'type'	=> $this->_type,
		));
    }
	
	public function actionEdit($linkId)
	{
		$entry = StudioLink::model()->findByPk($linkId);
		
		if (!in_array($this->_type, array_keys(StudioLink::model()->getTypes()))) {
			$this->redirect(array('studio/'));
		}
		
		if (!$entry || !$entry->isOwner()) {
			$this->redirect(array('studio/'));
		}
		
		if (isset($_POST['StudioLink'])) {
			$entry->attributes = $_POST['StudioLink'];
			if ($entry->validate()) {
				if ($this->_type == StudioLink::TYPE_BOOKMARK) {
					$entry->icon = '';
				}
				$entry->save();
				$this->redirect(array('studio/'));
			}
		}
		
		$this->setMemberPageTitle('Edit Link');
		
		$this->render('edit', array(
			'entry' => $entry,
			'type'	=> $this->_type,
		));
	}
	
	public function actionDelete($linkId = 0)
	{
        $ids = array();
        if (isset($_POST['ids']) && count($_POST['ids'])) {
            $ids = $_POST['ids'];
        } elseif ($linkId) {
            $ids = array(intval($linkId));
        }
		
        foreach ($ids as $id) {
			$entry = StudioLink::model()->findByPk($id);
			if ($entry && $entry->isOwner()) {
				$entry->delete();	
			}
        }
        
        if (Yii::app()->getRequest()->isAjaxRequest) {
            $this->sendJsonSuccess();
        } else {
			$this->redirect(array('studio/'));
        }
	}
	
	public function actionSort()
	{
		if (Yii::app()->getRequest()->isAjaxRequest) {
			if (!in_array($this->_type, array_keys(StudioLink::model()->getTypes()))) {	
				$this->sendJsonError(array('msg' => 'Something went wrong. Please reload the page and try again.'));
			}
			
			if (isset($_POST['studio-link'])) {
				foreach ($_POST['studio-link'] as $k => $id) {
					$entry = StudioLink::model()->findByPk($id);
					if ($entry && $entry->isOwner()) {
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