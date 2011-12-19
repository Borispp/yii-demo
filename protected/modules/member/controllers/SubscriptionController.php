<?php
class SubscriptionController extends YsaMemberController
{
	public function actionList()
	{
		
//		$this->render('list', array(
//				'event' => $event,
//				'entry' => $entry,
//			));
	}

	protected function _addNewTransaction(UserSubscription $obUserSubscription)
	{
		$obTransaction = new UserTransaction();
		$obTransaction->user_subscription_id = $obUserSubscription->id;
		$obTransaction->state = $obTransaction::STATE_CREATED;
		$obTransaction->created = date('Y.m.d H:i:s');
		$obTransaction->save();
		return $obTransaction;
	}

	public function actionNew()
	{
		$state = TRUE;
		$entry = new UserSubscription();
		$entry->user_id = $this->member()->id;
		$entry->state = UserSubscription::STATE_DISABLED;
		if(isset($_POST['UserSubscription']))
		{
			$entry->attributes=$_POST['UserSubscription'];
			$entry->discount = empty($_POST['UserSubscription']['discount']) ? '' : $_POST['UserSubscription']['discount'];
			if ($entry->validate() && $state) {
				$entry->save();
				$obTransaction = $this->_addNewTransaction($entry);
				$this->redirect(array('gotopaypal', 'id' => $obTransaction->id));
//				$this->setSuccessFlash("New entry successfully added. " . CHtml::link('Back to listing.', array('index')));
//				$this->redirect(array('edit', 'id'=>$entry->id));
			}
		}
		$this->render('new', array(
				'membershipList'	=> Membership::model()->findAll(),
				'entry'				=> $entry
			));
	}

	public function actionGoToPaypal()
	{
//		<?php
//class Flotheme_Paypal
//{
//	protected $_email;
//	protected $_currency;
//
//	protected function _getEmail()
//	{
//		if (empty($this->_email))
//			$this->_email = flotheme_get_option('paypal_email');
//		return $this->_email;
//	}
//
//	protected function _getCurrency()
//	{
//		if (empty($this->_currency))
//			$this->_currency = flotheme_get_option('currency');
//		return $this->_currency;
//	}
//
//	public function buyOne($itemName, $summ, $email = NULL, $currency = NULL, $returnUrl = NULL, $itemId = NULL)
//	{
//		$email = empty($email) ? $this->_getEmail() : $email;
//		$currency = empty($currency) ? $this->_getCurrency() : $currency;
//		if (empty($returnUrl) || !filter_var($returnUrl, FILTER_VALIDATE_URL))
//			$returnUrl = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
//		$summ = str_ireplace(',', '.', $summ);
//		return
//				'<input type="hidden" name="cmd" value="_xclick"/>
//		<input type="hidden" name="currency_code" value="'.$currency.'"/>
//		<input type="hidden" name="business" value="'.$email.'"/>
//		<input type="hidden" name="item_name" value="'.$itemName.'"/>'
//				.($itemId ? '<input type="hidden" name="item_number" value="'.$itemId.'"/>' : '').
//				'<input type="hidden" name="amount" value="'.$summ.'"/>'.
//				(flotheme_get_option('payment_mode') ? '' : '<input type="hidden" name="ipn_test" value="1"/>').
//				'<input type="hidden" name="notify_url" value="'.flotheme_get_permalink('notifications').'"/>
//		<input type="hidden" name="return" value="'.$returnUrl.'"/>';
//	}
//
//	public function url()
//	{
//		echo $this->getUrl();
//	}
//	public function getUrl()
//	{
//		if (!flotheme_get_option('payment_mode'))
//			return 'https://www.sandbox.paypal.com/cgi-bin/webscr';
//		else
//			return 'https://www.paypal.com/cgi-bin/webscr';
//	}
//	protected function _getUrl()
//	{
//		if (!flotheme_get_option('payment_mode'))
//		{
//			return 'ssl://sandbox.paypal.com';
//		}
//		else
//			return 'www.paypal.com';
//	}
//
//	protected function _getPort()
//	{
//		if (!flotheme_get_option('payment_mode'))
//		{
//			return 443;
//		}
//		else
//			return 80;
//	}
//
//	public function getBuyCart($products, $email = NULL, $currency = NULL, $returnUrl = NULL)
//	{
//		$result = '';
//		$email = empty($email) ? $this->_getEmail() : $email;
//		$currency = empty($currency) ? $this->_getCurrency() : $currency;
//		if (empty($returnUrl) || !filter_var($returnUrl, FILTER_VALIDATE_URL))
//			$returnUrl = get_bloginfo('siteurl');
//
//		$summ = str_ireplace(',', '.', $summ);
//		$result .=
//				'<input type="hidden" name="cmd" value="_cart"/>
//		<input type="hidden" name="upload" value="1"/>
//		<input type="hidden" name="business" value="'.$email.'"/>
//		<input type="hidden" name="currency_code" value="'.$currency.'"/>
//		<input type="hidden" name="return" value="'.$returnUrl.'"/>';
//		$counter = 0;
//		foreach($products as $data)
//		{
//			$result .=
//					'<input type="hidden" name="item_name_'.++$counter.'" value="'.$data['title'].'"/>
//			<input type="hidden" name="amount_'.$counter.'" value="'.str_ireplace(',', '.', $data['amount']).'"/>';
//		}
//		return $result;
//	}
//
//	public function buyCart($products, $email = NULL, $currency = NULL, $returnUrl = NULL)
//	{
//		echo $this->getBuyCart($products, $email, $currency, $returnUrl);
//	}
//
//	public function verify()
//	{
//		// read the post from PayPal system and add 'cmd'
//		$req = 'cmd=_notify-validate';
//
//		foreach ($_POST as $key => $value) {
//			$value = urlencode(stripslashes($value));
//			$req .= "&$key=$value";
//		}
//
//		// post back to PayPal system to validate
//		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
//		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
//		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
//		$fp = fsockopen ($this->_getUrl(), $this->_getPort(), $errno, $errstr, 30);
//
//		// assign posted variables to local variables
//		$item_name = $_POST['item_name'];
//		$item_number = $_POST['item_number'];
//		$payment_status = $_POST['payment_status'];
//		$payment_amount = $_POST['mc_gross'];
//		$payment_currency = $_POST['mc_currency'];
//		$txn_id = $_POST['txn_id'];
//		$receiver_email = $_POST['receiver_email'];
//		$payer_email = $_POST['payer_email'];
//
//		if (!$fp) {
//			return FALSE;
//		} else {
//			fputs ($fp, $header . $req);
//			while (!feof($fp)) {
//				$res = fgets ($fp, 1024);
//				if (strcmp ($res, "VERIFIED") == 0) {
//					return TRUE;
//					// check the payment_status is Completed
//					// check that txn_id has not been previously processed
//					// check that receiver_email is your Primary PayPal email
//					// check that payment_amount/payment_currency are correct
//					// process payment
//				}
//				else if (strcmp ($res, "INVALID") == 0) {
//					return FALSE;
//					// log for manual investigation
//				}
//			}
//			fclose ($fp);
//		}
//	}
//}
//		$this->renderVar();
	}

	public function actionIndex()
	{
		//Yii::app()->controller()->member();
		if ($this->member()->hasSubscription())
			$this->redirect(array('subscription/list/'));
		$this->redirect(array('subscription/new/'));
	}
}