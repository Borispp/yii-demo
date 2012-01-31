<?php
class FaqController extends YsaFrontController
{
	public function actionIndex()
	{
		$this->setFrontPageTitle(Yii::t('title', 'Faq'));
		
		$this->render('index',array(
			'faq'	=> Faq::model()->findAll(array(
					'condition' => 'state=:state',
					'params'    => array(':state' => Faq::STATE_ACTIVE),
				))
		));
	}
}