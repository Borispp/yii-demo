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
				array(
					'image' => Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide1.png',
					'caption' => 'a new way to <strong>market to<br/>your brides</strong> that encourages<br/>them to share your work',
				),
				array(
					'image' => Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide2.png',
					'caption' => 'debut at <br/><strong>WPPI LaunchPad <br/>Event</strong>&#133;',
				),
				array(
					'image' => Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide3.png',
					'caption' => 'connections to <strong>SmugMug</strong>, <br/> <strong>Zenfolio</strong>, and <strong>Pictage</strong> <br/>to allow for orders to <br/>be made from <strong>anywhere</strong>',
				),
				
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
		
		switch($error['code']) {
			case 403:
				$errCode = '403';
				break;
			case 404:
			default:
				$errCode = '404';
				break;
		}
		
		$page = Page::model()->findBySlug('page-' . $errCode);
		
        if($error) {
            if(Yii::app()->request->isAjaxRequest) {
                $this->sendJsonError(array(
					'msg' => $error['message'],
				));
            } else {
				
				$this->setFrontPageTitle($page->short);
				
                $this->render('error', array(
					'error'		=> $error,
					'page'		=> $page,
					'errCode'	=> $errCode,
				));
            }
        } else {
			$this->redirect(Yii::app()->homeUrl);
		}
    }
}