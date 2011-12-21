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
        
        if (!$app->filled()) {
            $this->redirect(array('wizard/'));
        }
		
		$this->breadcrumbs[] = 'Application';
		
		$this->setMemberPageTitle('Application');
        
        $this->render('view', array(
            'app' => $app,
        ));
    }
    
    public function actionCreate()
    {
        if ($this->member()->application()) {
            $this->redirect(array('application/'));
        }
        
        $app = new Application();
        
        if (isset($_POST['Application'])) {
            $app->attributes = $_POST['Application'];
            
            $app->setAttributes(array(
                'user_id'    => $this->member()->id,
                'state'      => Application::STATE_CREATED,
            ));
            
            $app->generateAppKey();
            $app->generatePasswd();
            
            if ($app->validate()) {
                $app->save();
                $this->redirect(array('wizard/'));
            }
        }
        
        $this->render('create', array(
            'app'   => $app,
        ));
    }
    
    public function actionEdit()
    {
        $app = $this->member()->application();
        
        if (!$app) {
            $this->redirect(array('application/create'));
        }
        
        $this->render('edit', array(
            'app' => $app,
        ));
    }
}