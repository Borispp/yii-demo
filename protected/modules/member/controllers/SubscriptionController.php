<?php
class SubscriptionController extends YsaMemberController
{
	protected function _addNewTransaction(UserSubscription $obUserSubscription)
	{
		$obTransaction = new UserTransaction();
		$obTransaction->user_subscription_id = $obUserSubscription->id;
		$obTransaction->state = $obTransaction::STATE_CREATED;
		$obTransaction->notes = $obUserSubscription->Membership->name;
		$obTransaction->created = date('Y.m.d H:i:s');
		$obTransaction->summ = $obUserSubscription->getSumm();
		$obTransaction->save();
		return $obTransaction;
	}

	/**
	 * @return YsaMemberPaypal
	 */
	protected function _getPayment()
	{
		static $object;
		if (!$object)
			$object = new YsaMemberPaypal();
		return $object;
	}

	public function actionList()
	{
		$this->renderVar('subscriptions', $this->member()->UserSubscription);
		$this->render('list');
	}

	public function actionNew()
	{
		$state = TRUE;
		$entry = new UserSubscription();
		$entry->user_id = $this->member()->id;
		$entry->state = UserSubscription::STATE_INACTIVE;
		if(isset($_POST['UserSubscription']))
		{
			$entry->attributes=$_POST['UserSubscription'];
			$entry->discount = empty($_POST['UserSubscription']['discount']) ? '' : $_POST['UserSubscription']['discount'];
			if ($entry->validate() && $state) {
				$entry->save();
				$obTransaction = $this->_addNewTransaction($entry);
				$this->redirect(array('paypal', 'id' => $obTransaction->id));
//				$this->setSuccessFlash("New entry successfully added. " . CHtml::link('Back to listing.', array('index')));
//				$this->redirect(array('edit', 'id'=>$entry->id));
			}
		}
		$this->render('new', array(
				'membershipList'	=> Membership::model()->findAll(),
				'entry'				=> $entry
			));
	}

	public function actionPaypal()
	{
		if (empty($_GET['id']) || !($obUserTransaction = UserTransaction::model()->findByPk($_GET['id'])) || !$obUserTransaction->UserSubscription)
		{
			if (!empty($_GET['id']) && $obUserTransaction && !$obUserTransaction->UserSubscription)
				$obUserTransaction->delete();
			return $this->render('error', array(
				'title'		=> 'Not found',
				'message'	=> 'No Transaction with such ID found'
			));
		}
		if ($obUserTransaction->UserSubscription->user_id != $this->member()->id)
		{
			return $this->render('error', array(
				'title'		=> 'Access denied',
				'message'	=> 'You are not allowed to access this tranaction.',
			));
		}
		if ($obUserTransaction->state == UserTransaction::STATE_PAID || $obUserTransaction->UserSubscription->isActive())
		{
			return $this->render('error', array(
				'title'		=> 'Already paid',
				'message'	=> 'You\'ve already paid this transaction.',
			));
		}
		$obUserTransaction->state = UserTransaction::STATE_SENT;
		$this->renderVar('currency', $this->_getPayment()->getCurrency());
		$this->renderVar('email', $this->_getPayment()->getEmail());
		$this->renderVar('productName', $obUserTransaction->notes);
		$this->renderVar('productId', $obUserTransaction->id);
		$this->renderVar('amount', $obUserTransaction->summ);
		$this->renderVar('notifyUrl', 'http://'.Yii::app()->request->getServerName().Yii::app()->createUrl('/member/subscription/notify/'));
		$this->renderVar('returnUrl', 'http://'.Yii::app()->request->getServerName().Yii::app()->createUrl('/member/subscription/notify/'));
		$this->renderVar('isTestMode', $this->_getPayment()->isTestMode());
		$this->renderVar('url', $this->_getPayment()->getUrl());
		$this->render('paypal');
	}

	public function actionNotify()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if (!$this->_getPayment()->verify())
				return $this->render('error', array(
				'title'		=> 'Verification failed',
				'message'	=> 'Transaction verification failed',
				));
			$obUserTransaction = UserTransaction::model()->findByPk($_POST['item_number']);
			$obUserTransaction->data = serialize($_POST);
			$obUserTransaction->state = UserTransaction::STATE_PAID;
			$obUserTransaction->payed = date('Y-m-d');
			$obUserTransaction->save();
			$obUserSubscription = $obUserTransaction->UserSubscription;
			$obUserSubscription->activate();
			return $this->render('ok', array(
				'obUserSubscription' => $obUserSubscription
			));
		}
		return $this->render('error', array(
			'title'		=> 'Access denied ',
			'message'	=> 'You are not allowed to access this page directly.',
		));
	}

	public function actionCancel()
	{

		$this->render('error', array(
			'message'	=> 'You canceled your subscription payment. If you have any questions — contact us.',
			'title'		=> 'Subscription payment canceled'
		));
	}

	public function actionIndex()
	{
		if ($this->member()->hasSubscription())
			$this->redirect(array('subscription/list/'));
		$this->redirect(array('subscription/new/'));
	}
}