<?php
class YsaActiveRecord extends CActiveRecord
{
	const STATE_ACTIVE = 1;
	const STATE_INACTIVE = 0;
	
	const FORMAT_DATE = 'Y-m-d';
	const FORMAT_DATETIME = 'Y-m-d H:i:s';

	const LEVEL = '—';

	const SEARCH_SESSION_NAME = 'ar_search';
	
	protected $_searchFields;

	public function getStates()
	{
		return array(
			self::STATE_ACTIVE      => 'Active',
			self::STATE_INACTIVE    => 'Inactive',
		);
	}

	public function state()
	{
		$states = $this->getStates();
		return $states[$this->state];
	}

	public function isActive()
	{
		return (int)$this->state == self::STATE_ACTIVE;
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class'				=> 'application.behaviors.YsaCTimestampBehavior',
				'createAttribute'	=> 'created',
				'updateAttribute'	=> 'updated',
			)
		);
	}

	public function searchOptions()
	{
		if (null !== Yii::app()->session[self::SEARCH_SESSION_NAME]) {
			$values = Yii::app()->session[self::SEARCH_SESSION_NAME];
		}

		$options = array(
			'keyword' => array(
				'label'     => 'Keyword',
				'type'      => 'text',
				'default'   => '',
				'value'     => isset($values['keyword']) ? $values['keyword'] : '',
			),
			'order_by' => array(
				'label' => 'Order By',
				'type'  => 'select',
				'options' => array(
					'id'    => 'ID',
					'name'  => 'Name',
				),
				'value'     => isset($values['order_by']) ? $values['order_by'] : '',
			),
			'order_sort' => array(
				'label' => 'Order Sort',
				'type'  => 'select',
				'options' => array(
					'ASC'  => 'ASC',
					'DESC' => 'DESC',
				),
				'value'     => isset($values['order_sort']) ? $values['order_sort'] : '',
			),
			'state' => array(
				'label'             => 'State',
				'type'              => 'select',
				'addEmptyOption'    => true,
				'options'           => Event::model()->getStates(),
				'value'     => isset($values['state']) ? $values['state'] : '',
			),
		);

		return $options;
	}

	public function searchCriteria($fields = null)
	{
		$criteria = new CDbCriteria();

		return $criteria;
	}

	public function setSearchFields($fields)
	{
		Yii::app()->session[self::SEARCH_SESSION_NAME] = $fields;
	}

	public function resetSearchFields()
	{
		unset (Yii::app()->session[self::SEARCH_SESSION_NAME]);
	}

	/**
	 *
	 * @param integer $id
	 * @param integer $userId
	 * @return object
	 */
	public function findByIdLogged($id, $userId = null)
	{
		if (null === $userId) {
			$userId = Yii::app()->user->id;
		}
		return $this->findByAttributes(array(
				'id'		=> (int) $id,
				'user_id'   => (int) $userId,
			));
	}

	public function encryptedId()
	{
		return YsaHelpers::encrypt($this->id);
	}
}