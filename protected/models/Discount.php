<?php

/**
 * This is the model class for table "discount".
 *
 * The followings are the available columns in table 'discount':
 * @property string $id
 * @property string $code
 * @property integer $state
 * @property double $summ
 * @property string $description
 */
class Discount extends CActiveRecord
{
	/**
	 * List of related memberships
	 * @var array
	 */
	public $membership_ids = array();

	/**
	 * Returns the static model of the specified AR class.
	 * @return Discount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function _clearMembershipRelations()
	{
		foreach($this->DiscountMembership as $obDiscountMembership)
			$obDiscountMembership->delete();
	}

	public function beforeSave()
	{
		$this->_clearMembershipRelations();
		if (empty($this->membership_ids))
			return parent::beforeSave();

		foreach($this->membership_ids as $id => $amount)
		{
			$obDiscountMembership = new DiscountMembership();
			$obDiscountMembership->amount = $amount;
			$obDiscountMembership->discount_id = $this->id;
			$obDiscountMembership->membership_id = $id;
			$obDiscountMembership->save();
		}
		return parent::beforeSave();
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'discount';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state', 'numerical', 'integerOnly'=>true),
			array('summ', 'numerical'),
			array('code', 'length', 'max'=>20),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, state, summ, description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'DiscountMembership'	=> array(self::HAS_MANY, 'DiscountMembership', 'discount_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'state' => 'State',
			'summ' => 'Summ',
			'description' => 'Description',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('summ',$this->summ);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
			));
	}

	public function findByCode($code)
	{
		return $this->model()->findByAttributes(array(
			'code'	=> $code
			));
	}

	public function isActive()
	{
		return (bool)$this->state;
	}

	/**
	 * Checks if discount can be used with given membership
	 * @param Membership $obMembership
	 * @return bool
	 */
	public function canBeUsed(Membership $obMembership)
	{
		$obDiscountMembership = DiscountMembership::model()->findByDiscountAndMembership($obMembership, $this);
		if (!$obDiscountMembership || !$obDiscountMembership->canBeUsed())
			return FALSE;
		return TRUE;
	}
}