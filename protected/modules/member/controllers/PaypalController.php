<?php
class PaypalController extends YsaMemberPayment
{
	protected $_email;
	protected $_mode;
	protected $_currency;
	protected $_returnUrl;
	protected $_notifyUrl;

	/**
	 * Prepare form fields
	 * @param string $notifyUrl
	 * @param string $returnUrl
	 * @return array
	 */
	protected function _getFormFields()
	{
		return array(
			'cmd'           => '_xclick',
			'mode'          => 'passthrough',
			'custom'        => $this->_sessionTransaction->type,
			'currency_code' => $this->getCurrency(),
			'business'      => $this->getEmail(),
			'item_name'     => $this->_sessionTransaction->name,
			'amount'        => $this->_sessionTransaction->summ,
			'item_number'   => $this->_sessionTransaction->item_id,
			'ipn_test'      => $this->isTestMode(),
			'notify_url'    => $this->_notifyUrl,
			'return'        => $this->_returnUrl,
		);
	}

	public  function getEmail()
	{
		if (empty($this->_email))
			$this->_email = Yii::app()->settings->get('paypal_user');
		return $this->_email;
	}

	public function isTestMode()
	{
		if (empty($this->_mode))
			$this->_mode = Yii::app()->settings->get('paypal_mode');
		return $this->_mode == 'test';
	}

	public  function getCurrency()
	{
		if (empty($this->_currency))
			$this->_currency = Yii::app()->settings->get('paypal_currency');
		return $this->_currency;
	}

	public function getReturnUrl()
	{
		return 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	}

	public function getUrl()
	{
		if ($this->isTestMode())
			return 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		else
			return 'https://www.paypal.com/cgi-bin/webscr';
	}

	protected function _getGateUrl()
	{
		if ($this->isTestMode())
		{
			return 'ssl://sandbox.paypal.com';
		}
		else
			return 'www.paypal.com';
	}

	protected function _getPort()
	{
		if ($this->isTestMode())
		{
			return 443;
		}
		else
			return 80;
	}

	public function verify()
	{
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';

		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}

		// post back to PayPal system to validate
		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		$fp = fsockopen ($this->_getGateUrl(), $this->_getPort(), $errno, $errstr, 30);

		// assign posted variables to local variables
		$item_name = $_POST['item_name'];
		$item_number = $_POST['item_number'];
		$payment_status = $_POST['payment_status'];
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id'];
		$receiver_email = $_POST['receiver_email'];
		$payer_email = $_POST['payer_email'];
		if ($payment_amount != $this->getSumm($_POST['custom'], $item_number))
		{
			YsaHelpers::log('Error with payment summ', array(
				'item_number'	=> $item_number,
				'amount_from_server' => $payment_amount,
				'real_amount' => $this->getSumm($_POST['custom'], $item_number)
			), 'error');
			return FALSE;
		}

		if (!$fp) {
			YsaHelpers::log('Error while opening fsockopen', array('item_number' => $item_number,), 'error');
			return FALSE;
		} else {
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {
					YsaHelpers::log('Transaction verified', array('item_number' => $item_number));
					return TRUE;
					// check the payment_status is Completed
					// check that txn_id has not been previously processed
					// check that receiver_email is your Primary PayPal email
					// check that payment_amount/payment_currency are correct
					// process payment
				}
				else if (strcmp ($res, "INVALID") == 0) {
					YsaHelpers::log('Transaction invalid', array('item_number' => $item_number), 'error');
					return FALSE;
					// log for manual investigation
				}
			}
			fclose ($fp);
		}
	}

	public function getOuterId()
	{
		return @$_POST['txn_id'];
	}

	/**
	 * @return PaymentTransaction
	 */
	public function createTransaction($type = NULL, $summ = NULL, $itemId = NULL)
	{
		if ($transaction = PaymentTransaction::model()->findByAttributes(array('outer_id' => $this->getOuterId())))
			return $transaction;
		if (strtolower(@$_POST['custom']) == 'application')
		{
			$app = Application::model()->findByPk($_POST['item_number']);
			return  $app->createTransaction(NULL, $summ);
		}
	}


	public function init()
	{
		parent::init();
		$this->crumb('Paypal');
		$this->_email = Yii::app()->settings->get('paypal_user');
		//$this->_mode = Yii::app()->settings->get('paypal_mode');
		$this->_currency = Yii::app()->settings->get('paypal_currency');
		$this->_returnUrl = $this->createAbsoluteUrl('/member/paypal/return/');
		$this->_notifyUrl = $this->createAbsoluteUrl('/member/paypal/catchnotification/');
	}

	public function actionProcess()
	{
		$this->renderVar('formFields', $this->_getFormFields());
		$this->renderVar('formAction', $this->getUrl());
		$this->setMemberPageTitle(Yii::t('payment', 'new_title'));
		$this->renderVar('memberEmail', $this->member()->email);
		YsaHelpers::log('Paypal processing', array('all_used_vars' => $this->_renderVars));
		$this->render('pay');
	}

	protected function _process()
	{
		YsaHelpers::log('Result from paypal server', array('post_data' => $_POST));
		if (!$this->getOuterId())
		{
			YsaHelpers::log('Error while processing — no outer ID found', array('post_data' => $_POST), 'error');
			$this->redirect(array('/member'));
		}
		$transaction = $this->createTransaction();
		$transaction->outer_id = $this->getOuterId();
		$transaction->notes = 'Paid by paypal';
		$transaction->data = serialize($_POST);
		$transaction->save();
		if ($state = $this->verify())
		{
			$transaction->setPaid();
		}
		return $state;
	}

	public function actionCatchNotification()
	{
		$this->_process();
	}

	public function actionReturn()
	{
		if ($this->_process())
		{
			$this->setSuccess(Yii::t('payment','payment_done'));
		}
		else
		{
			$this->setError(Yii::t('payment','payment_failed'));
		}
		$this->redirect($this->createTransaction()->getRedirectUrl());
	}
}
