<?php
class WizardController extends YsaMemberController
{
    public $defaultAction = 'application';
    
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
            $config['class'] = 'application.extensions.WizardBehavior';
            $this->attachBehavior('wizard', $config);
        }
        
        return parent::beforeAction($action);
    }

    public function actionApplication($step = null)
    {
        $app = $this->member()->application();
        
        if (!$app) {
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
        $this->render('finished', compact('event'));

        $event->sender->reset();
        Yii::app()->end();
    }
    
    public function wizardProcessStep($event)
    {
        $modelName = 'Wizard' . ucfirst($event->step);
        
        $model = new $modelName();
        
        $app = $this->member()->application();

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