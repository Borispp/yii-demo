<?php
class NewsletterController extends YsaFrontController
{
	public function actionSubscribe()
	{
		
		if (!Yii::app()->request->isAjaxRequest) {
			$this->redirect(Yii::app()->homeUrl);
		}
		
		$newsletterForm = new NewsletterForm();
		
		if (isset($_POST['NewsletterForm'])) {
			$newsletterForm->attributes = $_POST['NewsletterForm'];
			if ($newsletterForm->validate()) {
				
				VarDumper::dump($newsletterForm->attributes);
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
				
//				$mailchimp->
				
				
			}
		}
	}
}