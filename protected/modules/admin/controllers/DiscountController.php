<?php
class DiscountController extends YsaAdminController
{
	public function actionAdd()
	{
		$entry = new Discount();
		if(isset($_POST['Discount'])) {
			$entry->membership_ids = empty($_POST['Discount']['membership_ids']) ? array() : $_POST['Discount']['membership_ids'];
			$entry->attributes=$_POST['Discount'];
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

		$entry = Discount::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}
		if(Yii::app()->request->isPostRequest && isset($_POST['Discount'])) {
			$entry->membership_ids = empty($_POST['Discount']['membership_ids']) ? array() : $_POST['Discount']['membership_ids'];
			$entry->attributes=$_POST['Discount'];
			if($entry->save()) {
				$this->setSuccessFlash("Entry successfully updated. " . CHtml::link('Back to listing.', array('index')));
				$this->refresh();
			}
		}
		$this->setContentTitle('Edit Discount');
		$this->render('edit',array(
				'entry'     => $entry,
			));
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