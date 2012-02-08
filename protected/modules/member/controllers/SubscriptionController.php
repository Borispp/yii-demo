<?php
class SubscriptionController extends YsaMemberController
{
	public function init()
	{
		parent::init();

		$this->crumb('Settings', array('settings/'))
			 ->crumb('Subscriptions', array('subscription/'));
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
			'subscriptions' => $this->member()->payedSubscriptions,
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
				$transaction = $entry->createTransaction();
				$this->member()->activate();
				unset(Yii::app()->session['discount']);
				$this->redirect(array('payment/choosepayway/transactionId/'.$transaction->id));
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
			
			if ($entry->hasErrors())
			{
				$errors = array_shift( $entry->getErrors() );
				$this->setError( array_shift( $errors ) );
			}
			else
			{
				$this->setSuccess(Yii::t('save', 'subscription_discount_applied'));
				Yii::app()->session['discount'] = $_POST['UserSubscription']['discount'];
			}
		}
		
		$this->redirect( array('new') );
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
		if (!($obUserSubscription = UserSubscription::model()->findByPk($id)) || $obUserSubscription->user_id != $this->member()->id || UserSubscription::STATE_INACTIVE == $obUserSubscription->state)
		{
			$this->redirect(array('subscription/'));
		}
		$this->crumb('Subscription Details');
		$this->setMemberPageTitle('Subscription details');

		return $this->render('details', array('obUserSubscription' => $obUserSubscription));
	}
}