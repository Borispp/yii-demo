<?php
class PaymentController extends YsaAdminController
{
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria;

		$pagination = new CPagination(PaymentTransaction::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = PaymentTransaction::model()->findAll($criteria);

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

		$entry = PaymentTransaction::model()->findByPk($id);
		if (!$entry)
			$this->redirect('/admin/' . $this->getId());
		$this->setContentTitle('Transaction #'.$entry->id.' Details');
		$this->render('view',array(
				'entry'     => $entry,
			));
	}

	public function actionAdd()
	{
		$entry = new PaymentTransaction();
		if (isset($_POST['PaymentTransaction']))
		{
			$entry->attributes = $_POST['PaymentTransaction'];
			$entry->created = date('Y-m-d H:i:s');
			if ($entry->validate())
			{
				$entry->save();
				if (!empty($_POST['application_id']))
				{
					$application = Application::model()->findByPk($_POST['application_id']);
					$transactionApplication = new PaymentTransactionApplication();
					$transactionApplication->application_id = $application->id;
					$transactionApplication->transaction_id = $entry->id;
					$transactionApplication->save();
					$entry->setPaid();
				}
				$this->setSuccessFlash("New payment record successfully added. " . CHtml::link('Back to listing.', array('index')));
				$this->redirect(array('view', 'id'=>$entry->id));
			}
		}
		$this->setContentTitle('New transaction');
		$this->render('add',array(
			'entry'           => $entry,
			'applicationList' => Application::model()->findAllByAttributes(array(
				'paid' => 0
			))
		));
	}
}