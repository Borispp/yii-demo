<?php
class FaqController extends YsaAdminController
{
	public function actionAdd()
	{
		$entry = new Faq();
		if(isset($_POST['Faq'])) {
			$entry->attributes=$_POST['Faq'];
			if ($entry->validate())
			{
				$entry->save();
				$this->setSuccessFlash("Entry successfully added. " . CHtml::link('Back to listing.', array('index')));
				$this->redirect(array('edit', 'id'=>$entry->id));
			}
		}
		$this->setContentTitle('Add Faq');
		$this->render('add', array(
				'entry' => $entry,
			));
	}

	public function actionEdit($id)
	{
		$id = (int) $id;

		$entry = Faq::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}
		if(Yii::app()->request->isPostRequest && isset($_POST['Faq'])) {
			$entry->attributes=$_POST['Faq'];
			if($entry->save()) {
				$this->setSuccessFlash("Entry successfully updated. " . CHtml::link('Back to listing.', array('index')));
				$this->refresh();
			}
		}
		$this->setContentTitle('Edit question');
		$this->render('edit',array(
				'entry'     => $entry,
			));
	}

	public function actionIndex()
	{
		$entries = Faq::model()->findAll();
		$this->setContentTitle('Faq');
		$this->setContentDescription('manage questions.');
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
			Faq::model()->findByPk($id)->delete();
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
}