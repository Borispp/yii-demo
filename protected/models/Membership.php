<?php

/**
 * This is the model class for table "membership".
 *
 * The followings are the available columns in table 'membership':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property integer $duration
 * @property integer $active
 */
class Membership extends YsaActiveRecord implements YsaPayable
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Membership the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'membership';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, price, duration', 'required'),
			array('active', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('price', 'length', 'max'=>10),
			array('duration', 'numerical', 'min'=>1),
			array('price', 'numerical', 'numberPattern'=>'~^\d{1,10}(\.\d{1,2})?$~', 'message'=>'Price must be an unsigned number.'),
			array('description', 'safe'),
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
			'DiscountMembership'	=> array(self::HAS_MANY, 'DiscountMembership', 'membership_id'),
		);
	}

	public function canUseDiscount(Discount $obDiscount)
	{
		return DiscountMembership::model()->findByAttributes(array(
			'discount_id'	=> $obDiscount->id,
			'membership_id'	=> $this->id
		));
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'price' => 'Price',
			'active' => 'Active',
			'duration' => 'Duration (in months)'
		);
	}

	/**
	 * @return string in currency format
	 */
	public function price()
	{
		return Yii::app()->numberFormatter->formatCurrency($this->price, Yii::app()->params['currency']);
	}
	
	/**
	 * @return string in currency format
	 */
	public function discountedPrice(Discount $discount)
	{
		return Yii::app()->numberFormatter->formatCurrency($discount->recalc($this), Yii::app()->params['currency']);
	}
	
	public function findAllActive()
	{
		return self::model()->findAll('active=1');
	}

	public function getPayableId()
	{
		return $this->id;
	}

	public function getPayableName()
	{
		return $this->name;
	}

	public function getPayableDescription()
	{
		return $this->description;
	}

	public function getPayablePrice()
	{
		return $this->description;
	}
}