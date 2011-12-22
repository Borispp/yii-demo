<?php
Yii::import('zii.widgets.CBreadcrumbs');
class YsaMemberBreadcrumbs extends CBreadcrumbs
{
	public function init() {
		parent::init();
		
		$this->homeLink = YsaHtml::link('Home', array('/member'));
	}
}