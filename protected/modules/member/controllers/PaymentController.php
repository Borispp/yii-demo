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
		try
		{
			$redirectUrl = '';
			if (!in_array($type, array('application', 'subscription')))
			{
				throw new Exception('wrong_type');
				$redirectUrl = '/member/';
			}
			if ($type == 'application')
			{
				$redirectUrl = 'application/view/';
				if (!$itemId || !is_object($app = Application::model()->findByPk($itemId)))
				{
					throw new Exception('no_app_found');
				}
				if ($app->isPaid())
				{
					throw new Exception('application_already_paid');
				}
			}
		}
		catch(Exception $e)
		{
			$this->setError(Yii::t('payment',$e->getMessage()));
			$this->redirect(array($redirectUrl));
		}
	}

	/**
	 * @param $type
	 * @param $item_id
	 */
	public function actionChoosePayway($type, $itemId = NULL)
	{
		if ($type == 'application')
		{
			if ($this->member()->application)
				$itemId = $this->member()->application->id;
		}
		$this->_validateInputParams($type, $itemId);
		$this->setMemberPageTitle(Yii::t('payment', 'select_pay_system_title'));
		$this->render('choose_payway', array(
			'type'    => $type,
			'summ'    => $this->getSumm($type, $itemId),
			'item_id' => $itemId ,
		));
	}
}
