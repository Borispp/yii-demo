<?php
class OrderController extends YsaMemberController
{
	public function init() {
		parent::init();
		$this->crumb('Orders', array('order/'));
	}

	public function actionIndex()
	{
		$criteria = UserOrder::model()->searchCriteria();
		$pagination = new CPagination(UserOrder::model()->count($criteria));
		$pagination->pageSize = Yii::app()->params['admin_per_page'];
		$pagination->applyLimit($criteria);

		$entries = UserOrder::model()->findAll($criteria);

		$this->render('index',array(
				'entries'       => $entries,
				'pagination'    => $pagination
			));
	}
}