<?php
class OptionGroupController extends YsaAdminController
{
	public function actionAdd()
	{
		$entry = new OptionGroup();

		if(isset($_POST['OptionGroup'])) {
			$entry->attributes=$_POST['OptionGroup'];

			if (!$entry->slug) {
				$entry->generateSlugFromTitle();
			}

			if ($entry->validate()) {
				$entry->save();
				$this->setSuccessFlash("Entry successfully added. " . CHtml::link('Back to listing.', array('index')));
				$this->redirect(array('edit', 'id'=>$entry->id));
			}
		}

		$this->setContentTitle('Add Option Group');

		$this->render('add', array(
				'entry' => $entry,
			));
	}

	public function actionEdit($id)
	{
		$id = (int) $id;

		$entry = OptionGroup::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}

		if(Yii::app()->request->isPostRequest && isset($_POST['OptionGroup'])) {
			$entry->attributes=$_POST['OptionGroup'];
			if($entry->save()) {
				$this->setSuccessFlash("Entry successfully updated. " . CHtml::link('Back to listing.', array('index')));
				$this->refresh();
			}
		}

		$this->setContentTitle('Edit Option Group');

		$this->render('edit',array(
				'entry'     => $entry,
			));
	}

	public function actionIndex()
	{
		$entries = OptionGroup::model()->findAll();

		$this->setContentTitle('Option Groups');
		$this->setContentDescription('manage option groups.');

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
			OptionGroup::model()->findByPk($id)->delete();
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
}