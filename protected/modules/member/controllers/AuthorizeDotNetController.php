<?php
class AuthorizeDotNetController extends YsaMemberPayment
{
	protected $_apiLoginId;
	protected $_transactionKey;
	protected $transaction;
	protected $_testMode = TRUE;
	protected $_currency;

	/**
	 * Include everything needed.
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->_apiLoginId = Yii::app()->settings->get('authorizenet_api');
		$this->_transactionKey = Yii::app()->settings->get('authorizenet_transaction');
		$this->_testMode = (bool)Yii::app()->settings->get('authorizenet_mode');
		$this->_currency = Yii::app()->settings->get('authorizenet_currency');
		Yii::createComponent('application.extensions.authorizeNet.AuthorizeNet');
	}

	public function prepare($entry)
	{
		$transaction = $this->createTransaction();

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

	public function getFormUrl()
	{
		return 'https://test.authorize.net/gateway/transact.dll';
	}

	protected function _getFingerprint($summ, $itemId, $timestamp)
	{
		return AuthorizeNetSIM_Form::getFingerprint(
			$this->_apiLoginId,$this->_transactionKey,$summ, $itemId, $timestamp);
	}

	public function getFormFields()
	{
		$timestamp = time();
		return array(
			'x_type'         => 'AUTH_CAPTURE',
			'x_login'        => $this->_apiLoginId,
			'x_fp_hash'      => $this->_getFingerprint(
				$this->_sessionTransaction->summ,
				$this->_sessionTransaction->item_id,
				$timestamp
			),
			'x_amount'       => $this->_sessionTransaction->summ,
			'x_fp_timestamp' => $timestamp,
			'x_fp_sequence'  => $this->_sessionTransaction->item_id,
			'x_version'      => '3.1',
			'x_device_type'  => '1',
			'x_show_form'    => 'PAYMENT_FORM',
			'x_market_type'  => '2',
			'x_test_request' => 'true',
		);
	}

	/**
	 * @return PaymentTransaction
	 */
	public function createTransaction()
	{
		if (!$this->transaction)
		{
			if (strtolower($this->_sessionTransaction->type) == 'application')
			{
				$app = Application::model()->findByPk($this->_sessionTransaction->item_id);
				$this->transaction = $app->createTransaction(
					$this->_sessionTransaction->name,
					$this->_sessionTransaction->summ
				);
			}
		}
		return $this->transaction;
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

	public function actionProcess()
	{
		$entry = new YsaAuthorizeDotNet();
		$message = NULL;

		if (isset($_POST['YsaAuthorizeDotNet']))
		{
			$entry->attributes = $_POST['YsaAuthorizeDotNet'];
			if ($entry->validate() && ($message = $this->prepare($entry)) === TRUE)
			{
				$this->setSuccess(Yii::t('payment','payment_done'));
				$this->redirect($this->createTransaction()->getRedirectUrl());
			}
		}
		$this->renderVar('errorMessage', $message);
		$this->renderVar('entry', $entry);
		$this->renderVar('formAction',  $this->createAbsoluteUrl('/member/authorizedotnet/process/'));
		$this->render('process', array(
			'hidden_fields' => array(
				'type'    => $this->_sessionTransaction->type,
				'summ'    => $this->_sessionTransaction->summ,
				'item_id' => $this->_sessionTransaction->item_id
			)));
	}
}
