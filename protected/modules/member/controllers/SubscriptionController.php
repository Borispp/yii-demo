<?php
class SubscriptionController extends YsaMemberController
{
	public function init()
	{
		parent::init();
		
		$this->crumb('Settings', array('settings/'))
			 ->crumb('Subscriptions', array('subscription/'));
	}
	
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
		$this->setMemberPageTitle('Subscriptions');
		
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/subscriptionlist.js', CClientScript::POS_HEAD);
		
		$this->render('list', array(
			'subscriptions' => $this->member()->UserSubscription,
		));
	}

	public function actionNew()
	{
		$this->_cs->registerScriptFile(Yii::app()->baseUrl . '/resources/js/member/subscription.js', CClientScript::POS_HEAD);
		
		$state = TRUE;
		$entry = new UserSubscription();
		$entry->user_id = $this->member()->id;
		$entry->state = UserSubscription::STATE_INACTIVE;
		
		if (Yii::app()->getRequest()->getPost('remove_discount', false))
		{
			unset( Yii::app()->session['discount'] );
			Yii::app()->end();
		}
		
		if ( Yii::app()->session->contains('discount') )
		{
			$entry->discount = Yii::app()->session['discount'];
			$entry->Discount = Discount::model()->findByCode( $entry->discount );
		}
		
		if(isset($_POST['UserSubscription']))
		{
			$entry->attributes=$_POST['UserSubscription'];
			if ($entry->validate() && $state) 
			{
				$entry->save();
				$obTransaction = $this->_addNewTransaction($entry);
				unset( Yii::app()->session['discount'] );
				$this->redirect(array('paypal', 'id' => $obTransaction->id));
//				$this->setSuccessFlash("New entry successfully added. " . CHtml::link('Back to listing.', array('index')));
//				$this->redirect(array('edit', 'id'=>$entry->id));
			}
		}
		
		$this->setMemberPageTitle('New Subscription');		
		$this->crumb('Add Subscription');
		$this->setNotice( null ); // do not show default subscription notification
		
		$this->render('new', array(
			'membershipList'	=> Membership::model()->findAllActive(),
			'entry'				=> $entry
		));
	}

	/**
	 * Validate and set discount code to session
	 * 
	 * @throws CHttpException 
	 */
	public function actionDiscount()
	{
		if ( !Yii::app()->getRequest()->isPostRequest )
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.' );
		
		if ( !empty($_POST['UserSubscription']['discount']))
		{
			unset( Yii::app()->session['discount'] );
			$entry = new UserSubscription();
			$entry->discount = $_POST['UserSubscription']['discount'];
			$entry->validateDiscount();
			
			if ( $entry->hasErrors() )
			{
				$errors = array_shift( $entry->getErrors() );
				$this->setError( array_shift( $errors ) );
			}
			else
			{
				$this->setSuccess( 'Discount applied successfully!' );
				Yii::app()->session['discount'] = $_POST['UserSubscription']['discount'];
			}
		}
		
		$this->redirect( array('new') );
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
		
		$this->setMemberPageTitle('New Subscription');
		
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
	
	public function actionDelete($subscriptionId)
	{
		$ids = array();
		if (isset($_POST['ids']) && count($_POST['ids'])) {
			$ids = $_POST['ids'];
		} elseif ($subscriptionId) {
			$ids = array(intval($subscriptionId));
		}
		
		foreach ($ids as $id) {
			$entry = UserSubscription::model()->findByPk($id);
			
			if ($entry && $entry->user_id == $this->member()->id && UserSubscription::STATE_INACTIVE == $entry->state) {
				$entry->delete();
			}
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(array('subscription/'));
		}
	}

	public function actionDetails($id)
	{
		$this->setMemberPageTitle('Subscription details');
		if (!($obUserSubscription = UserSubscription::model()->findByPk($id)) || $obUserSubscription->user_id != $this->member()->id || UserSubscription::STATE_INACTIVE == $obUserSubscription->state)
		{
			$this->redirect(array('subscription/'));
		}
		return $this->render('details', array('obUserSubscription' => $obUserSubscription));
	}
}