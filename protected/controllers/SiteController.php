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
		
		$newsletterForm = new NewsletterForm();
		
		if (isset($_POST['NewsletterForm'])) {
			$newsletterForm->attributes = $_POST['NewsletterForm'];
			if ($newsletterForm->validate()) {
				
//				$key = Yii::app()->settings->get('mailchimp_key');
//				$listId = Yii::app()->settings->get('mailchimp_list_id');
//				$mailchimp = new MCAPI($key);
//				$subscribed = $mailchimp->listSubscribe($listId, $newsletterForm->email, array('NAME' => $newsletterForm->name));
				$subscribed = 1;
				if ($subscribed) {
					Yii::app()->user->setFlash('newsletterSubscribed', 'Thank you for subscribing our newsletter!');
					
					$this->refresh();
				} else {
					$newsletterForm->addError('name', 'Cannot subscribe to list. Please refresh page and try again.');
				}
			}
		}

        $this->render('index', array(
            'page' => $page,
			'newsletterForm' => $newsletterForm,
			'slides' => array(
				Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide1.png',
				Yii::app()->getBaseUrl(true) . '/resources/images/homepage/slide1.png',
			)
        ));
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