<?php
class ContactController extends YsaAdminController
{
	public function actionIndex()
	{
		$criteria = new CDbCriteria;
		$criteria->order = 'created DESC';

		$pagination = new CPagination(ContactMessage::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = ContactMessage::model()->findAll($criteria);
		
		$this->setContentTitle('Contact Messages');
		$this->setContentDescription('messages from the website.');

		$this->render('index', array(
			'entries' => $entries,
			'pagination' => $pagination,
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
			ContactMessage::model()->findByPk($id)->delete();
		}

		if (Yii::app()->getRequest()->isAjaxRequest) {
			$this->sendJsonSuccess();
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
}