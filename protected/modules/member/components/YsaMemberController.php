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
		if (!$this->_member || !$this->_member->hasSubscription())
			$this->setNotice('<div class="need-to-subscribe">You have no subscription. <a href="'.Yii::app()->createUrl('/member/subscription/').'">Subscribe now</a></div>');
		else
		{
			foreach(Notification::model()->getMemberNotifications($this->_member) as $obNotification)
			{
				$this->setNotice('<div class="admin-notification"><h4>'.$obNotification->title.'</h4>'.$obNotification->message.'</div>');
				$obNotification->read($this->_member);
			}
		}
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



