<?php

/**
 * This is the model class for table "discount_membership".
 *
 * The followings are the available columns in table 'discount_membership':
 * @property string $id
 * @property integer $membership_id
 * @property integer $discount_id
 * @property integer $amount
 */
class DiscountMembership extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return DiscountMembership the static model class
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
		return 'discount_membership';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('membership_id, discount_id, amount', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, membership_id, discount_id, amount', 'safe', 'on'=>'search'),
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
			'Discount'		=> array(self::BELONGS_TO, 'Discount', 'discount_id'),
			'Membership'	=> array(self::BELONGS_TO, 'Membership', 'membership_id'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'membership_id' => 'Membership',
			'discount_id' => 'Discount',
			'amount' => 'Amount',
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
		$criteria->compare('membership_id',$this->membership_id);
		$criteria->compare('discount_id',$this->discount_id);
		$criteria->compare('amount',$this->amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getMembership()
	{
		return Membership::model()->findByPk($this->membership_id);
	}
}