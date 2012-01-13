<?php
class WizardController extends YsaMemberController
{
    public $defaultAction = 'application';
    
	public function init() {
		parent::init();
		
		$this->setMemberPageTitle('Application Wizard');
	}
	
	public function beforeRender($view) {
		parent::beforeRender($view);
		
		$this->loadPlupload();
		
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/appwizardpage.js', CClientScript::POS_HEAD);
	
		return true;
	}
	
    public function beforeAction($action) 
    {
        $config = array();
        switch ($action->id) {
            case 'application':
                $config = array(
                    'steps'=>array(
                        'Logo'          => 'logo',
                        'Colors'        => 'colors',
                        'Fonts'         => 'fonts',
                        'Copyrights'    => 'copyrights',
                        'Submit'        => 'submit',
                    ),
                    'defaultBranch'=>false,
                    'events'=>array(
                        'onStart'=>'wizardStart',
                        'onProcessStep'=>'wizardProcessStep',
                        'onFinished'=>'wizardFinished',
                        'onInvalidStep'=>'wizardInvalidStep'
                    ),
                    'menuLastItem'=>'Finished'
                );
                break;
            default:
                break;
        }
        
        if (!empty($config)) {
            $config['class'] = 'application.extensions.YsaWizardBehavior';
            $this->attachBehavior('wizard', $config);
        }
        
        return parent::beforeAction($action);
    }

    public function actionApplication($step = null)
    {
        if (!$this->member()->application) {
            $this->redirect(array('application/create'));
        }
        
        $this->process($step);
    }
    
    public function wizardStart($event)
    {
        $event->handled = true;
    }
    
    public function wizardFinished($event)
    {
        $event->sender->reset();	
		$this->redirect(array('application/'));
        Yii::app()->end();
    }
    
    public function wizardProcessStep($event)
    {
        $modelName = 'Wizard' . ucfirst($event->step);
        $model = new $modelName();
        $app = $this->member()->application;

        $model->setApplication($app)
              ->loadDefaultValues();
        
        if (isset($_POST['step'])) {
            $model->prepare()->save();
            
            $event->sender->save($model->attributes);
            $event->handled = true;
            
        } else {
            $this->render($event->step, compact('event', 'model', 'app'));
        }
    }
    
    public function wizardInvalidStep($event)
    {
        Yii::app()->getUser()->setFlash('notice', $event->step . ' is not a vaild step in this wizard');
    }
}