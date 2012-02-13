<?php
class PageController extends YsaFrontController
{
	protected $_slug;
	
	public function actionView($slug)
	{
		$page = Page::model()->findBySlug($slug);
		
		if (!$page) {
			throw new CHttpException(404, 'Page not found.');
		}
		
		$this->_slug = $slug;

		$this->setMeta($page->meta());
		
		$this->setFrontPageTitle($page->title);

		$this->render('view', array(
			'page' => $page,
		));
	}
	
	public function actionContact()
	{
		$page = Page::model()->findBySlug('contact');
		
		$entry = new ContactMessage();
		
		if (isset($_POST['ContactMessage'])) {
			$entry->attributes = $_POST['ContactMessage'];
			if ($entry->validate()) {
				$entry->save();
				$entry->sendEmail();
				
				if ($entry->subscribe) {
					$key = Yii::app()->settings->get('mailchimp_key');
					$listId = Yii::app()->settings->get('mailchimp_list_id');
					$mailchimp = new MCAPI($key);
					$mailchimp->listSubscribe($listId, $entry->email, array('NAME' => $entry->name));
				}
				
				if (Yii::app()->request->isAjaxRequest) {
					$this->sendJsonSuccess(array(
						'msg' => Yii::t('notice', 'contact_thank_you'),
					));
				} else {				
					$this->setSuccess('Thank you for your message!');
					$this->refresh();					
				}
			}
			if (Yii::app()->request->isAjaxRequest) {
				$this->sendJsonError(array(
					'msg' => Yii::t('error', 'standart_error'),
				));
			}
		}
		
		$this->setFrontPageTitle($page->title);
		
		$this->setMeta($page->meta());
		
		$this->render('contact', array(
			'page' => $page,
			'entry' => $entry,
		));
	}
	
	/**
	* Declares class-based actions.
	*/
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}