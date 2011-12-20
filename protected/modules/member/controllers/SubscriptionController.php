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
		if (empty($_GET['id']) || !($obUserTransaction = UserTransaction::model()->findByPk($_GET['id'])))
		{
			$this->setError('No Transaction ID found');
			return $this->render('error');
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
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->_getPayment()->verify())
		{
			$obUserTransaction = UserTransaction::model()->findByPk($_POST['item_number']);
			$obUserTransaction->data = serialize($_POST);
			$obUserTransaction->state = UserTransaction::STATE_PAID;
			$obUserTransaction->save();
			$obUserSubscription = $obUserTransaction->getUserSubscription();
			$obUserSubscription->activate();
		}
		$this->render('ok', array(
			'obUserSubscription' => $obUserSubscription
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
		//Yii::app()->controller()->member();
		if ($this->member()->hasSubscription())
			$this->redirect(array('subscription/list/'));
		$this->redirect(array('subscription/new/'));
	}
}