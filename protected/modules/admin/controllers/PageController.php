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
		$criteria = new CDbCriteria;

		$entries = Page::model()->getOneLevelTree();

		$this->setContentTitle('Page Management');
		$this->setContentDescription('view all pages.');

		$this->render('index',array(
				'entries'   => $entries,
			));
	}

	// Uncomment the following methods and override them if needed
	/*
		public function filters()
		{
				// return the filter configuration for this controller, e.g.:
				return array(
						'inlineFilterName',
						array(
								'class'=>'path.to.FilterClass',
								'propertyName'=>'propertyValue',
						),
				);
		}

		public function actions()
		{
				// return external action classes, e.g.:
				return array(
						'action1'=>'path.to.ActionClass',
						'action2'=>array(
								'class'=>'path.to.AnotherActionClass',
								'propertyName'=>'propertyValue',
						),
				);
		}
		*/
}