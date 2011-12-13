<?php
class ClientsController extends YsaMemberController
{
    public function actionCreate()
    {
        $entry = new Client();

        if(isset($_POST['Client'])) {
            $entry->attributes = $_POST['Client'];
            $entry->owner_id = $this->_member->id;
            $entry->generatePassword();
            
            if ($entry->validate()) {
                $entry->save();
                $this->redirect(array('clients/'));
            }
        }

        $this->render('create', array(
            'entry' => $entry,
        ));
    }

    public function actionEdit()
    {
        $this->render('edit');
    }

    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'owner_id="' . $this->_member->id . '"';
        
        $pagination = new CPagination(Client::model()->count($criteria));
        $pagination->pageSize = Yii::app()->params['admin_per_page'];        
        $pagination->applyLimit($criteria);
        
        $entries = Client::model()->findAll($criteria);
        
        $this->render('index',array(
            'entries'   => $entries,
            'pagination'=> $pagination,
        ));
    }

    public function actionView()
    {
        $this->render('view');
    }

    // Uncomment the following methods and override them if needed
    /*
    public function filters()
    {
            // return the filter configuration for this controller, e.g.:
            return array(
                    'inlineFilterName',
                    array(
                            'class'=>'path.to.FilterClass',
                            'propertyName'=>'propertyValue',
                    ),
            );
    }

    public function actions()
    {
            // return external action classes, e.g.:
            return array(
                    'action1'=>'path.to.ActionClass',
                    'action2'=>array(
                            'class'=>'path.to.AnotherActionClass',
                            'propertyName'=>'propertyValue',
                    ),
            );
    }
    */
}