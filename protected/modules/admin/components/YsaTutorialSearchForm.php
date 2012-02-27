<?php

class YsaTutorialSearchForm extends YsaAdminFormModel
{
	public $keywords;	
	public $order_by;
	public $order_sort;
	public $state;
	public $category;
	
	/**
	 *
	 * @return \CDbCriteria 
	 */
	public function searchCriteria()
	{
		$criteria = new CDbCriteria();

		// search by keyword
		if (!empty($this->keywords))
		{
			$criteria->compare('title', $this->keywords, true, 'OR');
			$criteria->compare('content', $this->keywords, true, 'OR');
		}
		
		if (!empty($this->category)) {
			$criteria->compare('cat_id', $this->category);
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
		$attributes = Tutorial::model()->attributeNames();
		return array_combine(array_values($attributes), $attributes);
	}
	
	static public function stateOptions()
	{
		$member = new Tutorial;
		return $member->getStates();
	}
	
	public function categories()
	{
		$_categories = TutorialCategory::model()->findAll(array(
			'order' => 'rank ASC',
		));
		$categories = array(
			'' => '---',
		);
		foreach ($_categories as $cat) {
			$categories[$cat->id] = $cat->name;
		}
		
		
		return $categories;
	}
}
