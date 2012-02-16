<?php
abstract class YsaMemberPayment extends YsaMemberController
{
	/**
	 * @var YsaSessionTransaction
	 */
	protected $_sessionTransaction = NULL;

	public function init()
	{
		parent::init();
		if (!empty($_REQUEST['type']) && !empty($_REQUEST['item_id']) && !empty($_REQUEST['summ']))
		{
			$this->_sessionTransaction = new YsaSessionTransaction();
			$this->_sessionTransaction->type = $_REQUEST['type'];
			$this->_sessionTransaction->item_id = $_REQUEST['item_id'];
			$this->_sessionTransaction->summ = $_REQUEST['summ'];
			$this->_sessionTransaction->name = 'Application Initial Payment';
		}
		$this->crumb(Yii::t('payment','payment_title'), array('payment/'));
	}

	abstract public function actionProcess();

}