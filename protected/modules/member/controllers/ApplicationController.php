<?php
class ApplicationController extends YsaMemberController
{
    public $defaultAction = 'view';
    
    public function actionView()
    {
        $app = $this->member()->application;
		
        // new member -> redirect to application creation
        if (null === $app) {
            $this->redirect(array('application/create'));
        }
        
        if (!$app->filled()) {
            $this->redirect(array('wizard/'));
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
                $this->redirect(array('wizard/'));
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
		
		$this->crumb('Application', array('application/'))
			 ->crumb('Edit');
        
        $this->render('edit', array(
            'app' => $app,
        ));
    }
	
	public function actionSettings()
	{
        $app = $this->member()->application;
        
        if (!$app) {
            $this->redirect(array('application/create'));
        }
		
		$this->setMemberPageTitle('Application Settings');
		
        $this->render('settings', array(
            'app' => $app,
        ));
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
}