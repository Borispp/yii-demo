<?php
class PaymentController extends YsaMemberController
{
	/**
	 * @var PaymentTransaction
	 */
	protected $_transaction = NULL;

	protected function _getPaymentData()
	{
		return (object)array(
			'type'    => $_REQUEST['type'],
			'summ'    => $_REQUEST['summ'],
			'item_id' => $_REQUEST['item_id'],
		);
	}

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

	public function actionChoosePayway($type, $summ, $item_id)
	{
		$this->setMemberPageTitle(Yii::t('payment', 'select_pay_system_title'));
		$this->render('choose_payway', array(
			'type'    => $type,
			'summ'    => $summ,
			'item_id' => $item_id,
		));
	}

	/**
	 * @param $payway
	 * @return void
	 */
	public function actionPay($payway)
	{
		$returnUrl = 	$this->createAbsoluteUrl('/member/payment/return/', array(
			'payway'         => $payway,
		));
		$notifyUrl = 	$this->createAbsoluteUrl('/member/payment/catchnotification/', array(
			'payway'         => $payway,
		));

		$this->renderVar('formFields',
			$this->_getPayment($payway)->getFormFields(
				$this->_getPaymentData()->type,
				$this->_getPaymentData()->summ,
				$this->_getPaymentData()->item_id,
				$notifyUrl,
				$returnUrl
			)
		);
		$formAction = $this->_getPayment($payway)->getFormUrl();

		/**
		 * @todo Change the way it works
		 */
		if ($payway != 'paypal')
		{
			$formAction = $this->createAbsoluteUrl('/member/payment/processAuthorizeDotNet/');
			$entry = new YsaAuthorizeDotNet();
			$this->renderVar('entry', $entry);
			$this->renderVar('hidden_fields', array(
				'type'    => $this->_getPaymentData()->type,
				'summ'    => $this->_getPaymentData()->summ,
				'item_id' => $this->_getPaymentData()->item_id,
			));
		}
		$this->renderVar('formAction', $formAction);


		$this->setMemberPageTitle(Yii::t('payment', 'new_title'));

		//		$this->_getTransaction()->state = PaymentTransaction::STATE_SENT;
		//		$this->_getTransaction()->save();

		$this->renderVar('memberEmail', $this->member()->email);
		$this->render($this->_getPayment($payway)->getTemplateName());
	}

	protected function _process($payway)
	{
		if (!$this->_getPayment($payway)->getOuterId())
		{
			$this->redirect(array('/member'));
		}

		$transaction = $this->_getPayment($payway)->createTransaction();
		$transaction->outer_id = $this->_getPayment($payway)->getOuterId();
		$transaction->data = serialize($_POST);
		$transaction->save();
		$this->_transaction = $transaction;
		if ($state = $this->_getPayment($payway)->verify())
		{
			$transaction->setPaid();
		}
		return $state;
	}

	/**
	 * Custom method for authorize.net
	 * @todo MAKE IT NOT SO CUSTOM
	 * @param $payway
	 * @return void
	 */
	public function actionProcessAuthorizeDotNet()
	{
		$paymentProcessor = new YsaMemberAuthorizeNet();
		$entry = new YsaAuthorizeDotNet();

		if (isset($_POST['YsaAuthorizeDotNet']))
		{
			$entry->attributes = $_POST['YsaAuthorizeDotNet'];
			if ($entry->validate())
			{
				if (($message = $paymentProcessor->prepare($_REQUEST['type'], $_REQUEST['summ'], $_REQUEST['item_id'], $entry)) === TRUE)
				{
					$this->setSuccess(Yii::t('payment','payment_done'));
					$this->redirect($paymentProcessor->createTransaction($_REQUEST['type'], $_REQUEST['summ'], $_REQUEST['item_id'])->getRedirectUrl());
				}
				else
				{
					$this->renderVar('errorMessage', $message);
				}
			}
		}
		$this->renderVar('entry', $entry);
		$this->renderVar('formAction',  $this->createAbsoluteUrl('/member/payment/processAuthorizeDotNet/'));
		$this->render('authorizedotnet', array(
			'hidden_fields' => array(
				'type'    => $_REQUEST['type'],
				'summ'    => $_REQUEST['summ'],
				'item_id' => $_REQUEST['item_id']
			)));
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
