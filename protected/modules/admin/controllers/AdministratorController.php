<?php
class AdministratorController extends YsaAdminController
{
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria;
		$criteria->condition = 'role="admin"';

		$pagination = new CPagination(Admin::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = Admin::model()->findAll($criteria);

		$this->setContentTitle('Administrator Management');
		$this->setContentDescription('view all administrators.');

		$this->render('index',array(
				'entries'   => $entries,
				'pagination'=> $pagination,
			));
	}

	public function actionAdd()
	{
		$entry = new Admin();

		if(isset($_POST['Admin'])) {
			$entry->attributes=$_POST['Admin'];

			if ($entry->validate()) {
				$entry->role = Admin::ROLE_ADMIN;
				$entry->encryptPassword();
				$entry->save();
				$this->setSuccessFlash("New entry successfully added. " . CHtml::link('Back to listing.', array('index')));
				$this->redirect(array('edit', 'id'=>$entry->id));
			}
		}

		$this->setContentTitle('Add New Administrator');
		$this->setContentDescription('Fill the form to add new member.');

		$this->render('add',array(
				'entry'     => $entry,
			));
	}

	public function actionEdit($id)
	{
		$id = (int) $id;

		$entry = Admin::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}

		if(Yii::app()->request->isPostRequest && isset($_POST['User'])) {
			$entry->attributes=$_POST['User'];
			if($entry->save()) {
				$this->setSuccessFlash("Entry successfully updated. " . CHtml::link('Back to listing.', array('index')));
				$this->refresh();
			}
		}

		$this->setContentTitle($entry->name(), array('(view)', array('view', 'id' => $entry->id)));
		$this->setContentDescription('edit administrator details.');

		$this->render('edit',array(
				'entry'     => $entry,
			));
	}

	public function actionView($id)
	{
		$id = (int) $id;

		$entry = Admin::model()->findByPk($id);

		if (!$entry) {
			$this->redirect('/admin/' . $this->getId());
		}

		$this->setContentTitle($entry->name(), array('(edit)', array('edit', 'id' => $entry->id)));
		$this->setContentDescription('view member general information.');

		$this->render('view',array(
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
			Admin::model()->findByPk($id)->delete();
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
}