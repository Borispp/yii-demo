<?php
class MemberController extends YsaAdminController
{
    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'role="member"';
        
        $pagination = new CPagination(Member::model()->count($criteria));
        $pagination->pageSize = Yii::app()->params['admin_per_page'];        
        $pagination->applyLimit($criteria);
        
        $entries = Member::model()->findAll($criteria);
        
        $this->setContentTitle('Member Management');
        $this->setContentDescription('view all members.');

        $this->render('index',array(
            'entries'   => $entries,
            'pagination'=> $pagination,
        ));
    }
    
    public function actionAdd()
    {
        $entry = new Member();
        
        if(isset($_POST['Member'])) {
            $entry->attributes=$_POST['Member'];
            $entry->generateActivationKey();
            
            if ($entry->validate()) {
                $entry->role = Member::ROLE_MEMBER;
                $entry->encryptPassword();
                $entry->save();
                $this->setSuccessFlash("New entry successfully added. " . CHtml::link('Back to listing.', array('index')));
                $this->redirect(array('edit', 'id'=>$entry->id));
            }
        }
        
        $this->setContentTitle('Add New Member');
        $this->setContentDescription('Fill the form to add new member.');
        
        $this->render('add',array(
            'entry'     => $entry,
        ));
    }
    
    public function actionEdit($id)
    {
        $id = (int) $id;
        
        $entry = Member::model()->findByPk($id);
        
        if (!$entry) {
            $this->redirect('/admin/' . $this->getId());
        }
        
        if(Yii::app()->request->isPostRequest && isset($_POST['Member'])) {
            $entry->attributes=$_POST['Member'];
            if($entry->save()) {
                $this->setSuccessFlash("Entry successfully updated. " . CHtml::link('Back to listing.', array('index')));
                $this->refresh();
            }
        }
        
        $this->setContentTitle($entry->name(), array('(view)', array('view', 'id' => $entry->id)));
        $this->setContentDescription('edit member details.');
        
        $this->render('edit',array(
            'entry'     => $entry,
        ));
    }
    
    public function actionView($id)
    {
        $id = (int) $id;
        
        $entry = Member::model()->findByPk($id);
        
        if (!$entry) {
            $this->redirect('/admin/' . $this->getId());
        }
        
        $this->setContentTitle($entry->name(), array('(edit)', array('edit', 'id' => $entry->id)));
        $this->setContentDescription('view member general information.');
        
        $this->render('view',array(
            'entry'     => $entry,
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
            Member::model()->findByPk($id)->delete();
        }
        
        if (Yii::app()->getRequest()->isAjaxRequest) {
            $this->sendJsonSuccess();
        } else {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
    }
}