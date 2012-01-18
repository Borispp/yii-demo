<?php
class YsaWizardBehavior extends WizardBehavior
{
	public $menuProperties = array(
		'id'=>'wizard-breadcrumbs',
		'htmlOptions' => array(
			'class' => 'cf w-crumbs'
		),
		'activeCssClass'=>'active',
		'firstItemCssClass'=>'first',
		'lastItemCssClass'=>'last',
		'previousItemCssClass'=>'previous',
	);
	
	
	protected function generateMenuItems() {
		parent::generateMenuItems();
		// add z-index to all breadcrumb items
		$count = count($this->_menu->items);
		foreach ($this->_menu->items as $k => $item) {
			$this->_menu->items[$k]['linkOptions'] = array(
				'style' => 'z-index:' . ($count - $k) . ';'
			);
		}
	}
}