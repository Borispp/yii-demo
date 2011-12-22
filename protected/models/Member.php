<?php
/**
 * @property UserSubscription $UserSubscription
 */
class Member extends User
{
	protected $_application = null;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function relations()
	{
		return array(
			'UserSubscription'	=> array(self::HAS_MANY, 'UserSubscription', 'user_id'),
			'Membership'		=> array(self::MANY_MANY, 'Membership', 'user_subscription(user_id, membership_id)')
		)+parent::relations();
	}

	public function application()
	{
		if (null === $this->_application) {
			$this->_application = Application::model()->findByMember($this->id);
		}

		return $this->_application;
	}

	public function clients()
	{

	}

	public function hasSubscription()
	{
		foreach($this->UserSubscription as $obUserSubscription)
			if ($obUserSubscription->isActive())
				return TRUE;
		return FALSE;
	}
}