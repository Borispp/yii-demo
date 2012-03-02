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

		$this->_checkMember();
		$this->_showMemberStaticNotice();
		$this->_showInboxNotice();
		return true;
	}

	/**
	 * If there is no member redirect to home
	 * @return bool
	 */
	protected function _checkMember()
	{
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
	}

	protected function _showMemberStaticNotice()
	{
		if (!$this->member()) {
			$this->redirect(array('/login'));
		}
		if (!$this->member()->isActivated())
		{
			$message = 'You have not activated your account. Please, check your mail for activation link';
		}
		elseif (!$this->member()->application || !$this->member()->application->isPaid())
		{
			$message = Yii::t('notice', 'payment_offer', array(
				'{link}' => '<a href="'.Yii::app()->createUrl('/member/application/QuickCreate/').'">Pay now</a>'
			));
		}
		elseif (!$this->_member->hasSubscription())
		{
			$message = 'You have no subscription. <a href="'.
					Yii::app()->createUrl('/member/subscription/').'">Subscribe now</a>';
		}

		if (!empty($message))
		{
			$this->setStaticNotice(YsaHtml::tag('div', array('class' => 'need-to-subscribe')).
					$message.YsaHtml::closeTag('div'));
		}
	}

	/**
	 * Checks if there is any unread messages in Member's Inbox and shows notification about it
	 */
	protected function _showInboxNotice()
	{
		if (($inboxCount = StudioMessage::model()->memberCountUnread($this->member()))
				&& Yii::app()->controller->id != 'inbox')
		{
			$this->setStaticInfo(YsaHtml::tag('div', array('class' => 'has-new-mail')).
				Yii::t('notice', 'unread_in_inbox', array(
					'{count}'          => $inboxCount,
					'{message_ending}' => $inboxCount > 1 ? 's' : '',
					'{link}'           => YsaHtml::link('Go to Inbox', Yii::app()->createUrl('/member/inbox/')),
				)).YsaHtml::closeTag('div'));
		}
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



