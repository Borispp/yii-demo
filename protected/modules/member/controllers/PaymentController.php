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
	 * @param $payway
	 * @return void
	 */
	public function actionPay($payway)
	{
		$this->_checkTransaction();
		$returnUrl = 	$this->createAbsoluteUrl('/member/payment/return/', array(
			'payway'         => $payway,
			'transaction_id' => $this->_getTransaction()->id
		));
		$notifyUrl = 	$this->createAbsoluteUrl('/member/payment/catchnotification/', array(
			'payway'         => $payway,
			'transaction_id' => $this->_getTransaction()->id
		));

		$this->renderVar('formFields',
			$this->_getPayment($payway)->getFormFields(
				$this->_getTransaction(),
				$notifyUrl,
				$returnUrl
			)
		);
		$this->renderVar('formAction', $this->_getPayment($payway)->getFormUrl());

		$this->setMemberPageTitle(Yii::t('payment', 'new_title'));

		$this->_getTransaction()->state = PaymentTransaction::STATE_SENT;
		$this->_getTransaction()->save();
		$this->renderVar('memberEmail', $this->member()->email);
		$this->renderVar('prepareUrl', $this->createAbsoluteUrl('/member/payment/prepare/', array(
			'payway'         => $payway,
			'transaction_id' => $this->_getTransaction()->id
		)));
		$this->renderVar('enableRedirect', $this->_getPayment($payway)->enableRedirect());
		$this->render($this->_getPayment($payway)->getTemplateName());
	}

	protected function _process($payway)
	{
		if (!$this->_getPayment($payway)->getOuterId())
		{
			$this->redirect(array('/member'));
		}
		$this->_getTransaction()->outer_id = $this->_getPayment($payway)->getOuterId();
		$this->_getTransaction()->data = serialize($_POST);
		$this->_getTransaction()->save();
		if ($state = $this->_getPayment($payway)->verify())
		{
			$this->_getTransaction()->setPaid();
		}
		return $state;
	}

	/**
	 * Custom method for authorize.net
	 * @todo MAKE IT NOT SO CUSTOM
	 * @param $payway
	 * @return void
	 */
	public function actionPrepare($payway)
	{
		if ($this->_getPayment($payway)->prepare($this->_getTransaction()))
		{
			$this->setSuccess(Yii::t('payment','payment_done'));
		}
		else
		{
			$this->setError(Yii::t('payment','payment_failed'));
		}
		$this->redirect($this->_getTransaction()->getRedirectUrl());
	}

	public function actionCatchNotification($payway)
	{
		$this->_process($payway);
	}

	public function actionReturn($payway)
	{
		if ($this->_process($payway))
		{
			$this->setSuccess(Yii::t('payment','payment_done'));
		}
		else
		{
			$this->setError(Yii::t('payment','payment_failed'));
		}
		$this->redirect($this->_getTransaction()->getRedirectUrl());
	}
}