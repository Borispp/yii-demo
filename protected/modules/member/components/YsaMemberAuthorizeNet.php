<?php
class YsaMemberAuthorizeNet implements YsaMemberPayment
{
	protected $_apiLoginId = NULL;
	protected $_transactionKey = NULL;
	protected $_testMode = TRUE;

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
	public function prepare(PaymentTransaction $transaction)
	{
		$_p = array();
		$_p['amount'] = $transaction->summ;
		$_p['card_num'] = $_POST['cfull'];
		$_p['exp_date'] = (int) $_POST['v1'] . '/' . (int) $_POST['v2'];
		$_tmp = explode(' ',$_POST['full_name']);
		$_p['first_name'] = $_tmp[0];
		$_p['last_name'] = $_tmp[1];
		$_p['phone'] = (string) $_POST['phone'];
		$_p['email'] = (string) $_POST['email'];
		$_p['description'] = (string) $transaction->name;

		$authTransaction = new AuthorizeNetAIM(
			Yii::app()->settings->get('authorizenet_api'),
			Yii::app()->settings->get('authorizenet_transaction')
		);

		$authTransaction->setSandbox(true);

		$authTransaction->test_request = "TRUE";

		$authTransaction->amount = $_p['amount'];
		$authTransaction->card_num = $_p['card_num'];
		$authTransaction->exp_date = $_p['exp_date'];

		$authTransaction->first_name = $_p['first_name'];
		$authTransaction->last_name = $_p['last_name'];
		$authTransaction->company = 'Some compnay';
		$authTransaction->phone = $_p['phone'];
		$authTransaction->email = $_p['email'];
		$authTransaction->description = $_p['description'];

		$response = $authTransaction->authorizeAndCapture();

		if ($response->approved)
		{
			$transaction->outer_id = $response->transaction_id;
			$transaction->data = serialize(get_object_vars($response));
			$transaction->save();
			$transaction->setPaid();
			return TRUE;
		} else {
			$response->error_message;
			return FALSE;
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

	protected function _getFingerprint(PaymentTransaction $transaction, $timestamp)
	{
		return AuthorizeNetSIM_Form::getFingerprint(
			$this->_apiLoginId,
			$this->_transactionKey,
			$transaction->summ, $transaction->getItemId(), $timestamp);
	}

	public function getFormFields(PaymentTransaction $transaction, $notifyUrl, $returnUrl)
	{
		$timestamp = time();
		return array(
			'x_type'         => 'AUTH_CAPTURE',
			'x_login'        => $this->_apiLoginId,
			//			'x_tran_key'     => $this->_transactionKey,
			'x_fp_hash'      => $this->_getFingerprint($transaction, $timestamp),
			'x_amount'       => $transaction->summ,
			'x_fp_timestamp' => $timestamp,
			'x_fp_sequence'  => $transaction->getItemId(),
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
		/**
		 * <p><label class="main">email</label>
		<input type="text" value="" name="email"/>
		</p>

		<p><label class="main">phone</label>
		<input type="text" value="" name="phone"/>
		</p>

		<p><label class="main">payment for</label>
		<input type="text" value="" name="description"/>
		</p>

		<hr />

		<p><label class="main">card number</label>
		<input type="text" value="" name="cfull" class="ccnf"/>
		</p>

		<p class="validthru"><label class="main">valid thru</label>
		<label class="m">month</label>
		<label class="y">year</label>
		<label class="d">/</label>
		<select name="v1" class="v1">
		<option selected>--</option>
		<option value="01">01</option>
		<option value="02">02</option>
		<option value="03">03</option>
		<option value="04">04</option>
		<option value="05">05</option>
		<option value="06">06</option>
		<option value="07">07</option>
		<option value="08">08</option>
		<option value="09">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		</select>
		<select name="v2" class="v2">
		<option selected>--</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		</select>
		</p>

		<p><label class="main">name on card</label>
		<input type="text" value="" name="full_name"/>
		</p>

		<hr />

		<p><label class="main">choose amount</label>
		<input type="text" value="0.00" name="amount" class="amount" onBlur="this.value=formatCurrency(this.value);"/>
		</p>

		<p><input class="submit" type="submit" name="submit" value="make payment" />
		 */


		return array();
	}

	public function enableRedirect()
	{
		return FALSE;
	}

	/**
	 * @todo Check if save as verify
	 * @return void
	 */
	public function catchNotification()
	{

	}

}