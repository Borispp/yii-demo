<?php

class YsaApplicationSearchForm extends YsaAdminFormModel
{
	public $keywords;	
	public $order_by;
	public $order_sort;
	public $state;


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
		
		if (isset($this->state))
			$criteria->compare('state', $this->state);
		
		return $criteria;
	}
	
	static public function orderByOptions()
	{
		$attributes = Application::model()->attributeNames();
		return array_combine(array_values($attributes), $attributes);
	}
	
	static public function stateOptions()
	{
		return array(1 => 'Active', 0 => 'Inactive');
	}
	
	public function attributeLabels()
	{
		return array(
			'user_id' => 'Member ID',
		);
	}
}
