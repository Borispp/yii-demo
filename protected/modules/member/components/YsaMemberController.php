<?php
class YsaMemberController extends YsaController
{
	/**
	 * @var Member 
	 */
	protected $_member;

	public $breadcrumbs;
	
	public $memberPageTitle;

	public function accessRules()
	{
		//TODO: Functional Test of access rights
		return array(

			/* deny application submit
			array(
				'deny', 
				'roles' => array('interesant','member'),
				'expression' => $this->_matchModuleExpression(array('member')),
				'controllers' => array('application'), 
				'actions' => array('submit'), 
			),
			 */
			array(
				'deny', 
				'roles' => array('interesant'),
//				'expression' => $this->_matchModuleExpression(array('member')),
				'controllers' => array('settings'), 
				'actions' => array('index'),
				'verbs' => array('POST')
			),
			array(
				'allow', 
				'roles' => array('interesant'),
//				'expression' => $this->_matchModuleExpression(array('member')),
				'controllers' => array('application','settings','inbox','payment','default'), 
			),
			array('deny', 'roles' => array('interesant')),
			
			// allow guest notifications from external (paypal,authorize)
			array('allow', 'roles' => array('guest'), 'controllers' => array('paypal'), 'actions' => array('catchNotification')),
			
			array('allow', 'roles' => array('customer','member')),
			
			array('allow', 'actions' => array('delete','view','index','list'), 'roles' => array('expired_customer')),
			array('deny', 'roles' => array('expired_customer')),
			
			array('deny',  'users' => array('*')),
		);
	}

	/**
	 * @param array $modules
	 * @return clousure|string expression to be evaluated
	 */
	protected function _matchModuleExpression(array $modules)
	{
		$GLOBALS['_x_module'] = $module = isset($this->module) ? $this->module->getName() : false;
		$GLOBALS['_x_modules'] = $modules;
		return in_array($GLOBALS["_x_module"], $GLOBALS["_x_modules"]);
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
		if (!$this->_member)
		{
			try {
				if (Yii::app()->user->checkAccess('guest'))
					return true;	
				
				Yii::app()->user->logout();
				$this->redirect(Yii::app()->homeUrl);
			} catch (Exception $e) {
				Yii::app()->user->logout();
				$this->redirect(Yii::app()->homeUrl);
			}
		}
		
		if (!$this->_member->isActivated())
		{
			$mail_host = substr($this->_member->email, stripos($this->_member->email, '@')+1);
			$this->setStaticNotice('<div class="need-to-subscribe">You have not activated your account. Please, check your mail for activation link</div>');
		}
		elseif (!$this->_member->hasSubscription())
		{
			$this->setStaticNotice('<div class="need-to-subscribe">You have no subscription. <a href="'.Yii::app()->createUrl('/member/subscription/').'">Subscribe now</a></div>');
		}
		
		return true;
	}

	public function hasApplication()
	{
		if (!$this->member())
			return FALSE;
		return $this->member()->application;
	}
	
	/**
	 *
	 * @return Member
	 */
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
			$this->breadcrumbs[] = array(
				'label' => $name,
				'url'	=> $url,
			);
		} else {
			$this->breadcrumbs[] = array(
				'label'	=> $name,
			);
		}
		
		return $this;
	}
}



