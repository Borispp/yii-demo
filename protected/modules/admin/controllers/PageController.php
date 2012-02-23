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
		
		$this->loadPlupload();

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
	
	public function actionAddCustomField()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_POST['id'])) {
			$page = Page::model()->findByPk($_POST['id']);
			
			if (!$page) {
				$this->sendJsonError(array(
					'msg' => Yii::t('error', 'general_error'),
				));
			}
			
			$field = new PageCustom();
			$field->setAttributes(array(
				'page_id' => $page->id,
				'image'	=> '',
				'value' => '',
			));
			$field->setNextRank();
			$field->save();
			
			$this->sendJsonSuccess(array(
				'html' => $this->renderPartial('_customField', array(
					'field' => $field,
				), true),
			));
		}
		$this->redirect('index');
	}
	
	public function actionSaveCustomField()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_POST['id'])) {
			$field = PageCustom::model()->findByPk($_POST['id']);
			
			if (!$field) {
				$this->sendJsonError(array(
					'msg' => Yii::t('error', 'general_error'),
				));
			}
			
			$field->setAttributes(array(
				'name'	=> $_POST['name'],
				'value' => $_POST['value'],
			));
			$field->save();
			
			$this->sendJsonSuccess();
		}
		$this->redirect('index');
	}
	
	public function actionDeleteCustomField()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_POST['id'])) {
			$field = PageCustom::model()->findByPk($_POST['id']);
			
			if ($field) {
				$field->delete();
			}
			
			$this->sendJsonSuccess();
		}
		$this->redirect('index');
	}
	
	public function actionDeleteCustomFieldImage()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_POST['id'])) {
			$field = PageCustom::model()->findByPk($_POST['id']);
			
			if ($field) {
				$field->deleteImage();
			}
			
			$this->sendJsonSuccess(array(
				'html' => $this->renderPartial('_customLoad', array(
					'field'	=> $field,
				), true),
			));
		}
		$this->redirect('index');
	}
	
	public function actionLoadCustomImage($id)
	{
		$field = PageCustom::model()->findByPk($id);

		if (!$field) {
			$this->sendJsonError(array(
				'msg' => Yii::t('error', 'general_error'),
			));
		}

		if (isset($_FILES['file'])) {
			$field->upload('file');
			$image = $field->image();
			$this->sendJsonSuccess(array(
				'html' => $this->renderPartial('_customImage', array(
					'image' => $image,
					'field'	=> $field,
				), true)
			));
			
		} else {
			$this->sendJsonError(array(
				'msg' => Yii::t('error', 'general_error'),
			));
		}
	}
}