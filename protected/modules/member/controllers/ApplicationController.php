<?php
class ApplicationController extends YsaMemberController
{
    public $defaultAction = 'view';
    
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
	
    public function actionView()
    {
        $app = $this->member()->application;
		
		//$this->member()->simpleNotify("Here's my message. I would like to add some rows.<br/>Here's another one.<br/>And another one. A <a href='link'>link here</a>.", "Test member notification");
        // new member -> redirect to application creation
        if (null === $app) {
            $this->redirect(array('application/create'));
        }
        
        if (!$app->filled()) {
            $this->redirect(array('application/wizard/'));
        }
		
		$this->crumb('Application');
		
		$this->setMemberPageTitle('Application');
        
        $this->render('view', array(
            'app' => $app,
        ));
    }
    
    public function actionCreate()
    {
        if ($this->member()->application) {
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
				$app->fillWithStyle();
                $this->redirect(array('application/wizard/'));
            }
        }
		
		$this->crumb('Application');
		$this->setMemberPageTitle('Create Application');
        
        $this->render('create', array(
            'app'   => $app,
        ));
    }
    
    public function actionEdit()
    {
        $app = $this->member()->application;
        
        if (!$app) {
            $this->redirect(array('application/create'));
        }
		
		if (isset($_POST['Application'])) {
			$app->attributes = $_POST['Application'];
			
			if ($app->validate()) {
				$app->save();
				$this->setSuccess('Application has been successfully saved');
				$this->redirect(array('application/'));
			}
		}
		
		$this->crumb('Application', array('application/'))
			 ->crumb('Edit');
        
        $this->render('edit', array(
            'app' => $app,
        ));
    }
	
	public function actionPreview()
	{
        $app = $this->member()->application;
        
        if (!$app) {
            $this->redirect(array('application/create'));
        }
		
		$this->crumb('Application', array('application/'))
			 ->crumb('Preview');
		
		$this->setMemberPageTitle('Application Preview');
		
        $this->render('preview', array(
            'app' => $app,
        ));
	}
	
	public function actionSubmit()
	{
		$app = $this->member()->application;
        
        if (!$app) {
            $this->redirect(array('application/create'));
        }
		
		if ($app->filled()) {
			$app->submit();
			$app->lock();
			Yii::app()->user->setFlash('congrats', 'congrats');
			$this->redirect(array('congratulations'));
		} else {
			$this->redirect(array('view'));
		}
		
		
	}
	
	public function actionUpload($image)
	{
		$app = $this->member()->application;
		
		if (!$app) {
			$this->sendJsonError(array(
				'msg' => 'No application found. Please reload the page and try again.',
			));
		}
		
		if (!in_array($image, $app->getAvailableImages())) {
			$this->sendJsonError(array(
				'msg' => 'No available images found for this field. Please reload the page and try again.',
			));
		}
		
		$file = CUploadedFile::getInstanceByName('file');
		
		if (null === $file) {
			$this->sendJsonError(array(
				'msg' => 'No files uploaded. Please reload the page and try again.',
			));
		}
		
		if ($app->editOption($image, $file)) {
			$this->sendJsonSuccess(array(
				'html' => $this->renderPartial('/wizard/_image', array(
					'name'	=> $image,
					'image' => $app->option($image),
				), true)
			));
		} else {
			$this->sendJsonError(array(
				'msg' => 'Something went wrong. Please reload the page and try again.',
			));
		}
	}
	
	public function actionDelete($image)
	{
		$app = $this->member()->application;
		
		if (!$app) {
			$this->sendJsonError(array(
				'msg' => 'No application found. Please reload the page and try again.',
			));
		}
		
		if (!in_array($image, $app->getAvailableImages())) {
			$this->sendJsonError(array(
				'msg' => 'No available images found for this field. Please reload the page and try again.',
			));
		}
		
		$app->deleteOption($image);
		
		$this->sendJsonSuccess(array(
			'image'	=> $image,
			'html' => $this->renderPartial('/wizard/_upload', array(
					'name'	=> $image,
					'image' => $app->option($image),
				), true)
		));
	}
	
	public function actionWizard()
	{
		$app = $this->member()->application;
		
        if (null === $app) {
            $this->redirect(array('application/create'));
        }
		
		$models = array(
			'logo' => new WizardLogo(),
			'colors' => new WizardColors(),
			'fonts' => new WizardFonts(),
			'copyrights' => new WizardCopyrights(),
			'submit' => new WizardSubmit(),
		);
		
		foreach ($models as $k => $model) {
			$models[$k]->setApplication($app)
				->loadDefaultValues();
		}
		
		$this->render('wizard', array(
			'app' => $app,
			'models' => $models,
		));
	}
	
	public function actionLoadStep($step)
	{
		if (Yii::app()->request->isAjaxRequest) {
			
			$app = $this->member()->application;
			
			if (!$app) {
				$this->sendJsonError(array(
					'msg' => 'No application found.',
				));
			}
			
			if (!$app->filterWizardStep($step)) {
				$this->sendJsonError(array(
					'msg' => 'Invalid step. Please reload the page and try again.',
				));
			}
			
			$model = 'Wizard' . ucfirst($step);

			$view = '/wizard/' . $step;
			
			if (!class_exists($model)) {
				$this->sendJsonError(array(
					'msg' => 'Some errors occured. Please reload the page and try again.',
				));
			}
			
			$this->sendJsonSuccess(array(
				'html' => $this->renderPartial($view, array(
					'app' => $app,
				)),
			));
			
		} else {
			$this->redirect('application/');
		}
	}
	
	public function actionSaveStep($step)
	{
		if (Yii::app()->request->isAjaxRequest) {
			$app = $this->member()->application;
			
			if (!$app->filterWizardStep($step)) {
				$this->sendJsonError(array(
					'msg' => 'Invalid step. Please reload the page and try again.',
				));
			}
			
			$modelName = 'Wizard' . ucfirst($step);
			if (!class_exists($modelName)) {
				$this->sendJsonError(array(
					'msg' => 'Some errors occured. Please reload the page and try again.',
				));
			}
			
			if ('submit' == $step) {
				$data = array();
				$filled = $app->isProperlyFilled();
				
				if (is_array($filled)) {
					$data['fields'] = $filled;
					$data['error'] = 1;
					$data['msg'] = $app->generateFillErrorMsg($filled);
				} else {
					if (!$app->filled()) {
						$app->fill();					
					}
					
					if ($app->submitted()) {
						$data['redirectUrl'] = $this->createAbsoluteUrl('application/');
					} else {
						$data['redirectUrl'] = $this->createAbsoluteUrl('application/preview/');
					}
					
					
					$data['success'] = 1;
				}
				$this->sendJson($data);
				
			} else {
				$model = new $modelName();
				$model->setApplication($app)
					->loadDefaultValues();
				$model->prepare()->save();
				$this->sendJsonSuccess();
			}
		} else {
			$this->redirect('application/');
		}
	}
	
	public function actionCongratulations()
	{
		
		if (!Yii::app()->user->hasFlash('congrats')) {
			$this->redirect(array('application/'));
		}
		
		$page = Page::model()->findBySlug('wizard-congratulations');
		
		$this->setMemberPageTitle($page->title);
		
		$this->crumb('Application', array('application/'))
			 ->crumb('Sucessfully Submitted');
		
		$this->render('congratulations', array(
			'page' => $page,
		));
	}
	
	public function actionSupport()
	{
		$app = $this->member()->application;
		
        if (null === $app) {
            $this->redirect(array('application/create'));
        }
		
		// there are no tickets
		if (!$app->hasSupport() || !$app->ticket()) {
			$this->redirect(array('application/'));
		}
		
		$reply = new TicketReply();
		
		if (isset($_POST['TicketReply'])) {
			$reply->attributes = $_POST['TicketReply'];
			
			$reply->ticket_id = $app->ticket()->id;
			$reply->reply_by = $this->member()->id;
			
			if ($reply->validate()) {
				$reply->save();
				$this->setSuccess('Support Reply has been successfully added.');
				$this->refresh();
			}
		}
		
		$this->setMemberPageTitle('Application Support');
		
		$this->crumb('Application', array('application/'))
			 ->crumb('Support');
		
		$this->render('support', array(
			'app'		=> $app,
			'ticket'	=> $app->ticket(),
			'reply'		=> $reply
		));
	}
	
	public function actionLoadTemplate($template = 'dark')
	{
		$app = $this->member()->application;
		
        if (null === $app) {
            $this->redirect(array('application/create'));
        }
		
		$styles = $app->getStyles();
		
		if (in_array($template, array_keys($styles))) {
			$app->default_style = $template;
			$app->fillWithStyle();
			
			$this->setSuccess($styles[$template] . ' style is successfully loaded.');
		}
		
		$this->redirect(array('application/wizard/'));
	}
}