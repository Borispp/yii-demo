<?php
class DiscountController extends YsaAdminController
{
	public function actionAdd()
	{
		$entry = new Discount();
		if(isset($_POST['Discount'])) 
		{
			$entry->attributes=$_POST['Discount'];
			$this->_importMembershipData($entry);
			
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
				'memberships' => Membership::model()->findAllActive()
			));
	}

	public function actionEdit($id)
	{
		$id = (int) $id;
		$entry = Discount::model()->findByPk($id);
		$entry->loadMemebershipIds();

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}
		
		if(Yii::app()->request->isPostRequest && isset($_POST['Discount'])) 
		{
			$entry->attributes=$_POST['Discount'];
			$this->_importMembershipData($entry);
			
			if($entry->save()) 
			{
				$this->setSuccessFlash("Entry successfully updated. " . CHtml::link('Back to listing.', array('index')));
				$this->refresh();
			}
		}

		$this->setContentTitle('Edit Discount');
		$this->render('edit',array(
			'entry'     => $entry,
			'memberships' => Membership::model()->findAllActive()
		));
	}

	protected function _importMembershipData(Discount $discount)
	{
		$discount->membership_ids = array();
		if (!isset($_POST['Discount']['membership_ids']))
			return;
		
		foreach($_POST['Discount']['membership_ids'] as $membership_id)
		{
			$amount = empty($_POST['Discount']['membership_amounts'][$membership_id]) ? -1 
						: trim($_POST['Discount']['membership_amounts'][$membership_id]);
			$discount->membership_ids[$membership_id] = $amount;
		}
	}
	
	public function actionIndex()
	{
		$entries = Discount::model()->findAll();
		$this->setContentTitle('Discount');
		$this->setContentDescription('manage Discount Codes.');
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
			Discount::model()->findByPk($id)->delete();
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