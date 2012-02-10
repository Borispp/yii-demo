<?php
class YsaMemberPaypal implements YsaMemberPayment
{
	protected $_email;
	protected $_mode;
	protected $_currency;

	public function __construct()
	{
		$this->_email = Yii::app()->settings->get('paypal_user');
		//$this->_mode = Yii::app()->settings->get('paypal_mode');
		$this->_currency = Yii::app()->settings->get('paypal_currency');
	}

	public function getFormFields(PaymentTransaction $transaction, $notifyUrl, $returnUrl)
	{
		return array(
			'cmd'           => '_xclick',
			'currency_code' => $this->getCurrency(),
			'business'      => $this->getEmail(),
			'item_name'     => $transaction->name,
			'amount'        => $transaction->summ,
			'item_number'   => $transaction->getItemId(),
			'ipn_test'      => $this->isTestMode(),
			'notify_url'    => $notifyUrl,
			'return'        => $returnUrl,
		);
	}

	public function getFormUrl()
	{
		return $this->getUrl();
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

		if (!$fp) {
			return FALSE;
		} else {
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {
					return TRUE;
					// check the payment_status is Completed
					// check that txn_id has not been previously processed
					// check that receiver_email is your Primary PayPal email
					// check that payment_amount/payment_currency are correct
					// process payment
				}
				else if (strcmp ($res, "INVALID") == 0) {
					return FALSE;
					// log for manual investigation
				}
			}
			fclose ($fp);
		}
	}

	public function catchNotification()
	{
		var_dump($_REQUEST);die;
	}

	public function getOuterId()
	{
		return @$_POST['txn_id'];
	}

	public function getTemplateName()
	{
		return 'pay';
	}

	public function prepare(PaymentTransaction $transaction, YsaAuthorizeDotNet $entry)
	{
		return;
	}
}