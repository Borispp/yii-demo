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
				
				$key = Yii::app()->settings->get('mailchimp_key');
				$listId = Yii::app()->settings->get('mailchimp_list_id');
				$subscribed = 1;
				$mailchimp = new MCAPI($key);
				$subscribed = $mailchimp->listSubscribe($listId, $newsletterForm->email, array('NAME' => $newsletterForm->name));
				if ($subscribed) {
					$this->sendJsonSuccess(array(
						'msg' => Yii::t('newsletter', 'newsletter_subscribed'),
					));
				} else {
					$this->sendJsonError(array(
						'msg' => Yii::t('newsletter', 'newsletter_not_subscribed'),
					));
				}
			} else {
				$this->sendJsonError(array(
					'msg' => Yii::t('newsletter', 'newsletter_errors'),
				));
			}
		}
	}
}