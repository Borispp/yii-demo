<?php
class YsaMemberController extends YsaController
{
	protected $_member;

	protected $_uploadImagePath;

	protected $_uploadImageUrl;
	
	public $breadcrumbs;
	
	public $memberPageTitle;

	public function accessRules()
	{
		return array(
			array('allow', 'roles' => array('member')),
			array('deny',  'users' => array('*')),
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public $layout='//layouts/member';

	public function init()
	{
		parent::init();

		/**
		 * Load member
		 */
		$this->_member = Member::model()->findByPk(Yii::app()->user->getId());
		if (!$this->_member->hasSubscription())
			$this->setNotice('<div class="need-to-subscribe">You have no subscription. <a href="'.Yii::app()->createUrl('/member/subscription/').'">Subscribe now</a></div>');
	}

	/**
	 * Register web application's resources and meta.
	 * @param object $view
	 * @return bool
	 */
	public function beforeRender($view)
	{
		parent::beforeRender($view);

		$this->setMetaTitle(Yii::app()->settings->get('site_title'));

        Yii::app()->getClientScript()
			->registerCoreScript('jquery')
            ->registerMetaTag($this->getMetaDescription(), 'description')
            ->registerMetaTag($this->getMetaKeywords(), 'keywords')
				
			->registerScriptFile(Yii::app()->baseUrl . '/resources/js/jquery-ui.min.js', CClientScript::POS_HEAD)
            ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/plugins.js', CClientScript::POS_HEAD)
            ->registerScriptFile(Yii::app()->baseUrl . '/resources/js/screen.js', CClientScript::POS_HEAD)
			->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member.js', CClientScript::POS_HEAD)
			
			->registerCssFile(Yii::app()->baseUrl . '/resources/css/style.css')
			->registerCssFile(Yii::app()->baseUrl . '/resources/css/ui/jquery-ui.css')
			->registerCssFile(Yii::app()->baseUrl . '/resources/css/member.css');

		return true;
	}

	public function member()
	{
		return $this->_member;
	}
	
	public function setMemberPageTitle($title)
	{
		$this->memberPageTitle = $title;
	}
	
	public function crumb($name, $url = false)
	{
		if ($url) {
			$this->breadcrumbs[$name] = $url;
		} else {
			$this->breadcrumbs[] = $name;
		}
		
		return $this;
	}
}



