<?php
class SiteController extends YsaFrontController
{
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {	
        $page = Page::model()->findBySlug('homepage');
        $this->setMeta($page->meta());

		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins/fancybox.js', CClientScript::POS_END)
				  ->registerCssFile(Yii::app()->baseUrl . '/resources/css/plugins/fancybox.css');
//				  ->registerScriptFile('http://vjs.zencdn.net/c/video.js', CClientScript::POS_HEAD)
//				  ->registerCssFile('http://vjs.zencdn.net/c/video-js.css');
		
		
		$newsletterForm = new NewsletterForm();
		
        $this->render('index', array(
            'page' => $page,
			'newsletterForm' => $newsletterForm,
			'slides' => array(
				Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide1.png',
				Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide1.png',
			)
        ));
    }
	
	public function actionLoadVideo()
	{
		if (!Yii::app()->request->isAjaxRequest) {
			$this->redirect(Yii::app()->homeUrl);
		}
		
		$this->renderPartial('_video');
		Yii::app()->end();

//		$this->sendJsonSuccess(array(
//			'html' => $this->renderPartial('_video', array(), true)
//		));
	}

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        $error=Yii::app()->errorHandler->error;
		
		$this->setFrontPageTitle('Oops! Something went wrong...');
		
        if($error) {
            if(Yii::app()->request->isAjaxRequest) {
                $this->sendJsonError(array(
					'msg' => $error['message'],
				));
            } else {
                $this->render('error', $error);
            }
        }
    }
}