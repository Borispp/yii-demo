<?php
class SubscriptionController extends YsaAdminController
{
	public function actionAdd()
	{
		$entry = new UserSubscription();
		$this->renderVar('membership', Membership::model()->findAll());
		$this->renderVar('members', Member::model()->findAll());
		if(Yii::app()->request->isPostRequest && isset($_POST['UserSubscription']))
		{
			$entry->attributes=$_POST['UserSubscription'];
			$entry->start_date = $_POST['UserSubscription']['start_date'];
			$entry->expiry_date = $_POST['UserSubscription']['expiry_date'];
			$entry->state = $_POST['UserSubscription']['state'];
			if ($entry->validate())
			{
				$entry->save();
				$this->setSuccessFlash("Entry successfully added. " . CHtml::link('Back to listing.', array('index')));
				$this->redirect(array('edit', 'id'=>$entry->id));
			}
		}
		$this->setContentTitle('Add Discount');
		$this->render('add', array(
				'entry' => $entry,
			));
	}

	public function actionEdit($id)
	{
		$id = (int) $id;
		$entry = UserSubscription::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}
		if(Yii::app()->request->isPostRequest && isset($_POST['UserSubscription'])) {
			$entry->start_date = $_POST['UserSubscription']['start_date'];
			$entry->expiry_date = $_POST['UserSubscription']['expiry_date'];
			$entry->state = $_POST['UserSubscription']['state'];
			if($entry->save()) {
				$this->setSuccessFlash("Entry successfully updated. " . CHtml::link('Back to listing.', array('index')));
				$this->refresh();
			}
		}
		$this->setContentTitle('Edit Member Subscription');
		$this->render('edit',array(
				'entry'     => $entry,
			));
	}

	public function actionIndex()
	{
		$entries = UserSubscription::model()->findAll();
		$this->setContentTitle('Member Subscription');
		$this->setContentDescription('manage Member Subscriptions.');
		$this->render('index', array(
				'entries' => $entries,
			));
	}

	public function actionDelete()
	{
		$ids = array();
		if (isset($_POST['ids']) && count($_POST['ids'])) {
			$ids = $_POST['ids'];
		} elseif (isset($_GET['id'])) {
			$ids = array(intval($_GET['id']));
		}

		foreach ($ids as $id) {
			UserSubscription::model()->findByPk($id)->delete();
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}

	public function actionGetMembershipList()
	{
		$options = array();
		foreach(Membership::model()->findAll() as $obMembership)
			$options[$obMembership->id] = $obMembership->name;
		$this->sendJsonSuccess(array(
			'mebershipList' => $options,
		));
	}
}