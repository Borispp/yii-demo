<?php
class SiteController extends YsaController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
            return array(
                // captcha action renders the CAPTCHA image displayed on the contact page
                'captcha'=>array(
                        'class'=>'CCaptchaAction',
                        'backColor'=>0xFFFFFF,
                ),
            );
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
            $page = Page::model()->findBySlug('homepage');
            
            VarDumper::dump(Yii::app()->settings->getGroup('general'));
            
            $this->render('index', array(
                'page' => $page,
            ));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
            $error=Yii::app()->errorHandler->error;
	    if($error) {
	    	if(Yii::app()->request->isAjaxRequest) {
                    echo $error['message'];
                } else {
                    $this->render('error', $error);
                }
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate()) {
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
}