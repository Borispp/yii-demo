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
		$this->crumb(Yii::t('payment','payment_title'), array('payment/'));
	}

	protected function _checkTransaction($transactionId = NULL)
	{
		$errorMessage = NULL;
		$transaction = $this->_getTransaction($transactionId);
		if ($transaction->getMember()->id != $this->member()->id)
		{
			$errorMessage = Yii::t('payment', 'wrong_transaction_id');
		}
		if ($transaction->isPaid())
		{
			$errorMessage = Yii::t('payment', 'transaction_is_paid');
		}

		if ($errorMessage)
		{
			$this->setMemberPageTitle(Yii::t('payment','payment_error_title'));
			$this->render('error', array('message' => $errorMessage));
			die;
		}
	}

	public function actionChoosePayway($transactionId)
	{
		$this->_checkTransaction($transactionId);
		$this->setMemberPageTitle(Yii::t('payment', 'select_pay_system_title'));
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
		$this->_checkTransaction();
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

		$this->setMemberPageTitle(Yii::t('payment', 'new_title'));

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
			$this->setSuccess(Yii::t('payment','payment_done'));
			$this->_getTransaction()->setPaid();
		}
		else
		{
			$this->setError(Yii::t('payment','payment_failed'));
		}
		$this->redirect($this->_getTransaction()->getRedirectUrl());
	}
}