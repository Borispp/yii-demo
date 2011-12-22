<?php
class PaymentController extends YsaAdminController
{
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria;

		$pagination = new CPagination(UserTransaction::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = UserTransaction::model()->findAll($criteria);

		$this->setContentTitle('Payments');
		$this->setContentDescription('view all payments.');

		$this->render('index',array(
				'entries'   => $entries,
				'pagination'=> $pagination,
			));
	}

	public function actionView($id)
	{
		$id = (int) $id;

		$entry = UserTransaction::model()->findByPk($id);
		if (!$entry)
			$this->redirect('/admin/' . $this->getId());
		$this->setContentTitle('Transaction #'.$entry->id.' Details');
		$this->render('view',array(
				'entry'     => $entry,
			));
	}
}