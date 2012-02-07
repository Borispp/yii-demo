<?php
class PaymentController extends YsaMemberController
{
	/**
	 * @var PaymentTransaction
	 */
	protected $_transaction = NULL;

	/**
	 * @return YsaMemberPayment
	 */
	protected function _getPayment($payway)
	{
		static $object;
		if (empty($object[$payway]))
		{
			$object[$payway] = $payway == 'paypal' ? new YsaMemberPaypal() : new YsaMemberAuthorizeNet();
		}
		return $object[$payway];
	}

	/**
	 * @param null|int $transactionId
	 * @return PaymentTransaction
	 */
	protected function _getTransaction($transactionId = NULL)
	{
		if (!$this->_transaction)
		{
			$transactionId = $transactionId ? $transactionId : $_REQUEST['transaction_id'];
			$this->_transaction = PaymentTransaction::model()->findByPk($transactionId);
		}
		return $this->_transaction;
	}

	public function init()
	{
		parent::init();
		$this->crumb('Payment', array('payment/'));
	}

	public function actionChoosePayway($transactionId)
	{
		$this->setMemberPageTitle('Select Pay System');
		$this->render('choose_payway', array(
			'transaction'	=> $this->_getTransaction($transactionId)
		));
	}

	/**
	 * @todo add transaction validation
	 * @param $payway
	 * @return void
	 */
	public function actionPay($payway)
	{
//		if (empty($_GET['id']) || !($obUserTransaction = UserTransaction::model()->findByPk($_GET['id'])) || !$obUserTransaction->UserSubscription)
//		{
//			if (!empty($_GET['id']) && $obUserTransaction && !$obUserTransaction->UserSubscription)
//				$obUserTransaction->delete();
//			return $this->render('error', array(
//				'title'		=> 'Not found',
//				'message'	=> 'No Transaction with such ID found'
//			));
//		}
//		if ($obUserTransaction->UserSubscription->user_id != $this->member()->id)
//		{
//			return $this->render('error', array(
//				'title'		=> 'Access denied',
//				'message'	=> 'You are not allowed to access this tranaction.',
//			));
//		}
//		if ($obUserTransaction->state == UserTransaction::STATE_PAID || $obUserTransaction->UserSubscription->isActive())
//		{
//			return $this->render('error', array(
//				'title'		=> 'Already paid',
//				'message'	=> 'You\'ve already paid this transaction.',
//			));
//		}
		$backUrl = 'http://'.Yii::app()->request->getServerName().
			Yii::app()->createUrl('/member/payment/return/payway/'.$payway.'/transaction_id/'.$this->_getTransaction()->id);

		$this->renderVar('formFields',
			$this->_getPayment($payway)->getFormFields(
				$this->_getTransaction(),
				$backUrl,
				$backUrl
			)
		);
		$this->renderVar('formAction', $this->_getPayment($payway)->getFormUrl());

		$this->setMemberPageTitle('New Payment');

		$this->_getTransaction()->state = PaymentTransaction::STATE_SENT;
		$this->_getTransaction()->save();
		$this->render('pay');
	}

	public function actionReturn($payway)
	{
		if (!$this->_getPayment($payway)->getOuterId())
		{
			$this->redirect(array('/member'));
		}
		$this->_getTransaction()->outer_id = $this->_getPayment($payway)->getOuterId();
		$this->_getTransaction()->data = serialize($_POST);
		$this->_getTransaction()->save();

		if ($this->_getPayment($payway)->verify())
		{
			$this->setSuccess("Your application was successfully paid");
			$this->_getTransaction()->setPaid();
		}
		else
		{
			$this->setError("Payment failed.");
		}
		$this->redirect(array('application/'));
	}
}