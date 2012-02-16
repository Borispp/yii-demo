<?php
class YsaMemberAuthorizeNet implements YsaMemberPayment
{
	protected $_apiLoginId = NULL;
	protected $_transactionKey = NULL;
	protected $_testMode = TRUE;
	public $transaction;


	/**
	 * Include everything needed.
	 * @return void
	 */
	public function __construct()
	{
		$this->_apiLoginId = Yii::app()->settings->get('authorizenet_api');
		$this->_transactionKey = Yii::app()->settings->get('authorizenet_transaction');
		$this->_testMode = (bool)Yii::app()->settings->get('authorizenet_mode');
		$this->_currency = Yii::app()->settings->get('authorizenet_currency');
		Yii::createComponent('application.extensions.authorizeNet.AuthorizeNet');
	}

	public function verify()
	{

	}


	public function prepare($type, $summ, $itemId, YsaAuthorizeDotNet $entry)
	{
		$transaction = $this->createTransaction($type, $summ, $itemId);

		$authTransaction = new AuthorizeNetAIM(
			Yii::app()->settings->get('authorizenet_api'),
			Yii::app()->settings->get('authorizenet_transaction')
		);

		$authTransaction->setSandbox(true);

		$authTransaction->test_request = "TRUE";

		$authTransaction->amount = $transaction->summ;
		$authTransaction->card_num = $entry->card_number;
		$authTransaction->exp_date = $entry->getExpireDate();

		$authTransaction->first_name = $entry->getFirstName();
		$authTransaction->last_name = $entry->getLastName();
		$authTransaction->company = $entry->getCompanyName();
		$authTransaction->phone =$entry->phone;
		$authTransaction->email = $entry->email;
		$authTransaction->description = $transaction->name;

		$response = $authTransaction->authorizeAndCapture();

		if ($response->approved)
		{
			$transaction->outer_id = $response->transaction_id;
			$transaction->data = serialize(get_object_vars($response));
			$transaction->save();
			$transaction->setPaid();
			return TRUE;
		} else {
			return $response->error_message;
		}
	}

	public function getOuterId()
	{
		return;
	}

	public function getFormUrl()
	{
		return 'https://test.authorize.net/gateway/transact.dll';
	}

	protected function _getFingerprint($summ, $itemId, $timestamp)
	{
		return AuthorizeNetSIM_Form::getFingerprint(
			$this->_apiLoginId,$this->_transactionKey,$summ, $itemId, $timestamp);
	}

	public function getFormFields($type, $summ, $itemId, $notifyUrl, $returnUrl)
	{
		$timestamp = time();
		return array(
			'x_type'         => 'AUTH_CAPTURE',
			'x_login'        => $this->_apiLoginId,
			//			'x_tran_key'     => $this->_transactionKey,
			'x_fp_hash'      => $this->_getFingerprint($summ, $itemId, $timestamp),
			'x_amount'       => $summ,
			'x_fp_timestamp' => $timestamp,
			'x_fp_sequence'  => $itemId,
			'x_version'      => '3.1',
			'x_device_type'  => '1',
			'x_show_form'    => 'PAYMENT_FORM',
			'x_market_type'  => '2',
			'x_test_request' => 'true',
			//'x_method'       => 'cc',
			//
			//			'x_card_num'     => '370000000000002',
			//			'x_exp_date'     => '05-2012',
			//			'x_card_code'    => '1111'
		);
	}

	public function getTemplateName()
	{
		return 'authorizedotnet';
	}

	/**
	 * @todo Check if save as verify
	 * @return void
	 */
	public function catchNotification()
	{

	}

	/**
	 * @return PaymentTransaction
	 */
	public function createTransaction($type = NULL, $summ = NULL, $itemId = NULL)
	{
		if (!$this->transaction)
		{
			if (strtolower($type) == 'application')
			{
				$app = Application::model()->findByPk($itemId);
				$this->transaction = $app->createTransaction(NULL, $summ);
			}
		}
		return $this->transaction;
	}
}