<?php
class PhotoSizeController extends YsaAdminController
{
	public function actionIndex()
    {
        $entries = PhotoSize::model()->findAll();
     
        $this->setContentTitle('Photo Size');
        $this->setContentDescription('manage photo sizes.');
        
        $this->render('index', array(
            'entries' => $entries,
        ));
    }
    
    public function actionDelete()
    {
        $ids = array();
        if (isset($_POST['ids']) && count($_POST['ids'])) {
            $ids = $_POST['ids'];
        } elseif (isset($_GET['id'])) {
            $ids = array(intval($_GET['id']));
        }
        
        foreach ($ids as $id) {
            PhotoSize::model()->findByPk($id)->delete();
        }
        
        if (Yii::app()->getRequest()->isAjaxRequest) {
            $this->sendJsonSuccess();
        } else {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
    }
	
    public function actionAdd()
    {
        $entry = new PhotoSize();
        
        if(isset($_POST['PhotoSize'])) {
            $entry->attributes=$_POST['PhotoSize'];

            if ($entry->validate()) {
                $entry->save();
                $this->setSuccessFlash("Entry successfully added. " . CHtml::link('Back to listing.', array('index')));
                $this->redirect(array('edit', 'id'=>$entry->id));
            }
        }
        
        $this->setContentTitle('Add Photo Size');
        
        $this->render('add', array(
            'entry' => $entry,
        ));
    }

	
    public function actionEdit($id)
    {
        $id = (int) $id;
        
        $entry = PhotoSize::model()->findByPk($id);
        
        if (!$entry) {
            $this->redirect('/admin/' . $this->getId());
        }
        
        if(Yii::app()->request->isPostRequest && isset($_POST['PhotoSize'])) {
            $entry->attributes=$_POST['PhotoSize'];
            if($entry->save()) {
                $this->setSuccessFlash("Entry successfully updated. " . CHtml::link('Back to listing.', array('index')));
                $this->refresh();
            }
        }
        
        $this->setContentTitle('Edit Photo Size');
        
        $this->render('edit',array(
            'entry'     => $entry,
        ));
    }

}