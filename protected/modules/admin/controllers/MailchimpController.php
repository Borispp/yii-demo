<?php
class MailchimpController extends YsaAdminController
{
	public function actionIndex()
	{
		$this->setContentTitle('Mailchimp Newsletter Subscribers');
		$this->setContentDescription('subscribers from mailchimp.');

		$key = Yii::app()->settings->get('mailchimp_key');
		$listId = Yii::app()->settings->get('mailchimp_list_id');
		
		$mailchimp = new MCAPI($key);
		
		$lists = $mailchimp->lists(array(
			'list_id' => $listId
		));
		
		$list = null;
		if ($lists) {
			$list = $lists['data'][0];
		}
		
		$subscribers = $mailchimp->listMembers($listId);
		
		$this->render('index', array(
			'list' => $list,
			'subscribers' => $subscribers,
		));
	}
}