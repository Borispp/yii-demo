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
		$this->crumb(Yii::t('payment','payment_title'), array('payment/'));
		if (!empty($_REQUEST['type']) && !empty($_REQUEST['item_id']) && !empty($_REQUEST['summ']))
		{
			$this->_sessionTransaction = new YsaSessionTransaction();
			$this->_sessionTransaction->type = $_REQUEST['type'];
			$this->_sessionTransaction->item_id = $_REQUEST['item_id'];
			$this->_sessionTransaction->summ = $_REQUEST['summ'];
			$this->_sessionTransaction->name = 'Application Initial Payment';
			$this->crumb(Yii::t('payment','select_pay_system_title'),
				array('payment/ChoosePayway/type/'.$_REQUEST['type'].'/item_id/'.$_REQUEST['item_id'])
			);
		}

	}

	/**
	 * @todo Add case for subscription
	 * @param string $type
	 * @param integer $itemId
	 * @return float
	 */
	public function getSumm($type, $itemId)
	{
		if ($type == 'application')
		{
			return Yii::app()->settings->get('application_summ');
		}
	}

	abstract public function actionProcess();
}