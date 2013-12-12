<?php
class TutorialCategoryController extends YsaAdminController
{
	public function actionIndex()
	{
		$entries = TutorialCategory::model()->findAll(array(
			'order' => 'rank ASC',
		));

		$this->setContentTitle('Tutorial Categories Management');
		$this->setContentDescription('view all categories.');

		$this->render('index',array(
			'entries'   => $entries,
		));
	}
	
	public function actionAdd()
	{
		$entry = new TutorialCategory();
		if(isset($_POST['TutorialCategory'])) {
			$entry->attributes=$_POST['TutorialCategory'];
			if ($entry->validate())
			{
				$entry->save();
				$this->setSuccessFlash("Entry successfully added. " . YsaHtml::link('Back to listing.', array('index')));
				$this->redirect(array('edit', 'id'=>$entry->id));
			}
		}
		$this->setContentTitle('Add Tutorial Category');
		$this->render('add', array(
			'entry' => $entry,
		));
	}

	public function actionEdit($id)
	{
		$id = (int) $id;

		$entry = TutorialCategory::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}
		if(Yii::app()->request->isPostRequest && isset($_POST['TutorialCategory'])) {
			$entry->attributes=$_POST['TutorialCategory'];
			if($entry->save()) {
				$this->setSuccessFlash("Entry successfully updated. " . YsaHtml::link('Back to listing.', array('index')));
				$this->refresh();
			}
		}
		$this->setContentTitle('Edit Tutorial Category');
		$this->render('edit',array(
			'entry'     => $entry,
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
			TutorialCategory::model()->findByPk($id)->delete();
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
}