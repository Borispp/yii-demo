<?php
class YsaSearchBar extends CWidget 
{
	const TYPE_TEXT = 'text';
	const TYPE_SELECT = 'select';
	const TYPE_CALENDAR = 'calendar';
	const TYPE_CHECKBOX = 'checkbox';
	
	const BUTTON_RESET_ID = 'search-bar-reset-btn';
	const FIELD_RESET_NAME = 'SearchBarReset'; 
	const FIELD_BAR_NAME = 'SearchBar';

	public $searchOptions;
	protected $_emptyVal = array('' => '---');

	public function run() 
	{
		foreach ($this->searchOptions as $k => $v) {
			// set default value if not set
			if (!isset($this->searchOptions[$k]['default'])) {
				$this->searchOptions[$k]['default'] = $v['default'] = '';
			}
			// set value
			$this->searchOptions[$k]['value'] = isset($v['value']) ? $v['value'] : $v['default'];
			
			// add empty option to dropdown list if needed
			if (isset($v['addEmptyOption']) && $v['addEmptyOption']) {
				$this->searchOptions[$k]['options'] = $this->_emptyVal + $v['options'];
			}
		}
		
		// render search bar
		$this->render('YsaSearchBar', array(
			'searchOptions' => $this->searchOptions,
		));
	}
}