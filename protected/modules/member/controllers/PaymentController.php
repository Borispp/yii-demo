<?php
class PaymentController extends YsaMemberController
{
	/**
	 * @todo Add case for subscription
	 * @param string $type
	 * @param integer $itemId
	 * @return float
	 */
	public function getSumm($type, $itemId)
	{
		if ($type == 'application')
		{
			return Yii::app()->settings->get('application_summ');
		}
	}

	/**
	 * Check if object can be paid
	 * @param $type
	 * @param $itemId
	 */
	protected function _validateInputParams($type, $itemId)
	{
		if ($type == 'application')
		{
			$app = Application::model()->findByPk($itemId);
			try
			{
				if (!is_object($app))
				{
					throw new Exception(Yii::t('payment','no_app_found'));
				}
				if ($app->user_id != $this->member()->id)
				{
					throw new Exception(Yii::t('payment','not_your_app'));
				}
				if ($app->isPaid())
				{
					throw new Exception(Yii::t('payment','application_already_paid'));
				}
			}
			catch(Exception $e)
			{
				$this->setError($e->getMessage());
				$this->redirect(array('application/view/'));
			}
		}
	}

	/**
	 * @param $type
	 * @param $item_id
	 */
	public function actionChoosePayway($type, $item_id)
	{
		$this->_validateInputParams($type, $item_id);
		$this->setMemberPageTitle(Yii::t('payment', 'select_pay_system_title'));
		$this->render('choose_payway', array(
			'type'    => $type,
			'summ'    => $this->getSumm($type, $item_id),
			'item_id' => $item_id,
		));
	}
}
