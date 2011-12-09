<?php

class ApplicationController extends YsaMemberController
{
    public $defaultAction = 'view';
    
    public function actionView()
    {        
        
        $app = $this->member()->application();
        
        // new member -> redirect to application creation
        if (null === $app) {
            $this->redirect(array('application/create'));
        }
        
        VarDumper::dump($app);
        
        $this->render('view');
    }
    
    public function actionCreate()
    {
        if ($this->member()->application()) {
            $this->redirect(array('application'));
        }
        
        $app = new Application();
        
        if (isset($_POST['Application'])) {
            $app->attributes = $_POST['Application'];
            
            $app->setAttributes(array(
                'user_id'    => $this->_member->id,
                'state'      => Application::STATE_CREATED,
            ));
            
            $app->generateAppKey();
            $app->generatePasswd();
            
            if ($app->validate()) {
                
                $app->save();
                
                $this->redirect(array('application/wizard'));
            }
        }
        
        $this->render('create', array(
            'app'   => $app,
        ));
    }


    public function actionWizard()
    {
        $app = $this->member()->application();
        
        
        if (!$app) {
            $this->redirect(array('application/create'));
        }
        
        $this->render('wizard', array(
            'app' => $app,
        ));
        
    }
    

    public function actionEdit()
    {
            $this->render('edit');
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