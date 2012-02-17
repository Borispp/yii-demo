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

	public function actionChoosePayway($type, $item_id)
	{
		$this->setMemberPageTitle(Yii::t('payment', 'select_pay_system_title'));
		$this->render('choose_payway', array(
			'type'    => $type,
			'summ'    => $this->getSumm($type, $item_id),
			'item_id' => $item_id,
		));
	}
}
