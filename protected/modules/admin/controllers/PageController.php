<?php

class PageController extends YsaAdminController
{
	public function actionAdd()
	{
		$entry = new Page();

		if(isset($_POST['Page'])) {
			$entry->attributes=$_POST['Page'];

			if (!$entry->slug) {
				$entry->generateSlugFromTitle();
			}

			if ($entry->validate()) {
				$entry->save();
				// create new meta for page
				if (isset($_POST['Meta'])) {
					$entry->meta()->attributes = $_POST['Meta'];
					$entry->meta()->elm = 'page';
					$entry->meta()->elm_id = $entry->id;
					$entry->meta()->save();
				}
				$this->setSuccessFlash("New entry successfully added. " . CHtml::link('Back to listing.', array('index')));
				$this->redirect(array('edit', 'id'=>$entry->id));
			}
		}

		$this->setContentTitle('Add New Page');
		$this->render('add', array(
			'entry' => $entry,
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
			Page::model()->findByPk($id)->delete();
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}

	public function actionEdit($id)
	{
		$id = (int) $id;

		$entry = Page::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}

		if(isset($_POST['Page'])) {
			$entry->attributes=$_POST['Page'];

			if (!$entry->slug) {
				$entry->generateSlugFromTitle();
			}

			if ($entry->validate()) {
				$entry->save();
				// update meta
				if (isset($_POST['Meta'])) {
					$entry->meta()->attributes = $_POST['Meta'];
					$entry->meta()->save();
				}
				$this->setSuccessFlash("Entry successfully updated. " . CHtml::link('Back to listing.', array('index')));
				$this->refresh();
			}
		}

		$this->setContentTitle('Edit Page');
		$this->render('edit', array(
				'entry' => $entry,
			));
	}

	public function actionIndex()
	{
		$entries = Page::model()->getOneLevelTree();

		$this->setContentTitle('Page Management');
		$this->setContentDescription('view all pages.');

		$this->render('index',array(
				'entries'   => $entries,
			));
	}
}