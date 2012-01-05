<?php

class SettingsController extends YsaAdminController
{
	public function actionAdd($group)
	{
		$group = OptionGroup::model()->findBySlug($group);

		if (!$group) {
			$this->redirect(array('/admin/'));
		}

		$entry = new Option();

		$this->setContentTitle('Add Option');
		$this->setContentDescription('add new option to group ' .$group->title );

		if(isset($_POST['Option'])) {
			$entry->attributes=$_POST['Option'];

			$entry->name = YsaHelpers::filterSystemName($entry->name, '_');
			$entry->group_id = $group->id;

			if ($entry->validate()) {
				$entry->save();
				$this->redirect(array('settings/group/' . $group->slug));
			}
		}

		$this->render('add', array(
				'group' => $group,
				'entry' => $entry,
			));
	}

	public function actionEdit($group, $id)
	{
		$group = OptionGroup::model()->findBySlug($group);

		if (!$group) {
			$this->redirect(array('/admin/'));
		}

		$id = (int) $id;

		$entry = Option::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}

		if(Yii::app()->request->isPostRequest && isset($_POST['Option'])) {
			$entry->attributes=$_POST['Option'];
			if($entry->save()) {
				$this->setSuccessFlash("Entry successfully updated.");
				$this->redirect(array('settings/group/' . $group->slug));
			}
		}

		$this->setContentTitle('Edit Option');

		$this->render('edit',array(
				'entry'     => $entry,
			));
	}

	public function actionIndex($group = '')
	{
		$group = OptionGroup::model()->findBySlug($group);


		if (!$group) {
			$this->redirect(array('/admin/'));
		}

		if (isset($_POST['id'])) {

			$options = $group->options();

			foreach ($options as $option) {
				switch ($option->type_id) {
					case Option::TYPE_CHECKBOX:
						if (isset($_POST['id'][$option->id])) {
							$option->value = (int) $_POST['id'][$option->id];
						} else {
							$option->value = 0;
						}
						break;

					case Option::TYPE_IMAGE:
						if (isset($_POST['id'][$option->id])) {
							$option->image()
									->upload('image' . $option->id, $option->getOptionOptionsList())
									->save();
							$option->value = $option->image()->id;
						}
						break;

					default:
						if (isset($_POST['id'][$option->id])) {
							$option->value = $_POST['id'][$option->id];
						}
						break;
				}
				$option->save();
			}
			$this->setSuccessFlash("Options successfully updated.");
			$this->refresh();
		}

		$this->setContentTitle($group->title);
		$this->setContentDescription('manage options.');

		$this->render('index', array(
				'group' => $group,
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
			Option::model()->findByPk($id)->delete();
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}

	public function actionRemoveImage()
	{
		if (isset($_POST['id']) && Yii::app()->getRequest()->isAjaxRequest) {

			$option = Option::model()->findByPk($_POST['id']);

			if (!$option) {
				$this->sendJsonError();
			}

			$option->image()->delete();
			$option->value = '';
			$option->save();

			$this->sendJsonSuccess(array(
					'html' => $option->renderField(),
				));

		}
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