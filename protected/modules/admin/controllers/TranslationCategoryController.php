<?php
class TranslationCategoryController extends YsaAdminController
{
	public function actionAdd()
	{
		$entry = new TranslationCategory();
		if(isset($_POST['TranslationCategory'])) {
			$entry->attributes=$_POST['TranslationCategory'];
			if ($entry->validate())
			{
				$entry->save();
				$this->setSuccessFlash("Entry successfully added. " . YsaHtml::link('Back to listing.', array('index')));
				$this->redirect(array('edit', 'id'=>$entry->id));
			}
		}
		$this->setContentTitle('Add Translation Category');
		$this->render('add', array(
				'entry' => $entry,
			));
	}

	public function actionEdit($id)
	{
		$id = (int) $id;

		$entry = TranslationCategory::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}
		if(Yii::app()->request->isPostRequest && isset($_POST['TranslationCategory'])) {
			$entry->attributes=$_POST['TranslationCategory'];
			if($entry->save()) {
				$this->setSuccessFlash("Entry successfully updated. " . YsaHtml::link('Back to listing.', array('index')));
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
		$entries = TranslationCategory::model()->findAll(array(
            'order' => 'name ASC',
        ));
		$this->setContentTitle('Translation Category');
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
			TranslationCategory::model()->findByPk($id)->delete();
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
}