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

		$this->loadFancybox();
		
		$newsletterForm = new NewsletterForm();
		
        $this->render('index', array(
            'page' => $page,
			'newsletterForm' => $newsletterForm,
			'slides' => array(
				array(
					'image' => Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide1.png',
					'caption' => 'Deliver a <strong>new and incredible<br/>experience</strong> to your clients<br/>through <strong>your own mobile<br/>application</strong>',
				),
				array(
					'image' => Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide2.png',
					'caption' => 'Seamless <strong>connection to your<br/>workflow</strong> and the companies<br/><strong>you love</strong>',
				),
				array(
					'image' => Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide3.png',
					'caption' => 'Now featuring Shoot and Share<br/>integration with <strong>PASS</strong> &ndash;<br/>the new way for professionals<br/>to <strong>share their images</strong>',
				),
				array(
					'image' => Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide4.png',
					'caption' => 'Allows your work to be <strong>seen</strong>,<br/><strong>shared</strong>, and <strong>ordered more<br/>conveniently</strong> than ever!',
				),
				array(
					'image' => Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide5.png',
					'caption' => 'Debuting in WPPI at the<br/>LaunchPad Event on<br/><strong>February 19th</strong>',
				),
			),
        ));
    }
	


	
	
	public function actionLoadVideo()
	{
		if (!Yii::app()->request->isAjaxRequest) {
			$this->redirect(Yii::app()->homeUrl);
		}
		
		$this->renderPartial('_video');
		Yii::app()->end();
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