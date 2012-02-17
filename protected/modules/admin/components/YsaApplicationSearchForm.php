<?php

class YsaApplicationSearchForm extends YsaAdminFormModel
{
	public $keywords;	
	public $order_by;
	public $order_sort;
	public $state;
	public $paid;
	public $filled;


	/**
	 *
	 * @return \CDbCriteria 
	 */
	public function searchCriteria()
	{
		$criteria = new CDbCriteria();

		if (!empty($this->keywords))
		{
			$criteria->compare('name', $this->keywords, true, 'OR');
			$criteria->compare('info', $this->keywords, true, 'OR');
		}

		if (!empty($this->order_by) && !empty($this->order_sort))
		{
			if (in_array($this->order_by, $this->orderByOptions()) && array_key_exists($this->order_sort, $this->orderSortOptions()))
			{
				$criteria->order = $this->order_by.' '.$this->order_sort;
			}
		}
		
		if (isset($this->state) && $this->state != '-1')
			$criteria->compare('state', $this->state);
		
		if (isset($this->paid) && $this->paid != '-1')
			$criteria->compare('paid', $this->paid);
		
		if (isset($this->filled) && $this->filled != '-1')
			$criteria->compare('filled', $this->filled);
		
		return $criteria;
	}
	
	static public function orderByOptions()
	{
		$attributes = Application::model()->attributeNames();
		return array_combine(array_values($attributes), $attributes);
	}
	
	static public function stateOptions()
	{
		return array(-1 => 'All', 1 => 'Active', 0 => 'Inactive');
	}
	
	static public function paidOptions()
	{
		return array(-1 => 'All', 1 => 'Paid', 0 => 'Unpaid');
	}
	
	static public function filledOptions()
	{
		return array(-1 => 'All', 1 => 'Filled', 0 => 'Not filled');
	}
	
	public function attributeLabels()
	{
		return array(
			'user_id' => 'Member ID',
		);
	}
}
