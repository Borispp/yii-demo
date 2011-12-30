<?php

/**
 * This is the model class for table "user_order_photo".
 *
 * The followings are the available columns in table 'user_order':
 * @property integer $id
 * @property integer $order_id
 * @property integer $photo_id
 * @property integer $quantity
 * @property string $size
 * @property string $style

 * @property UserOrder $order
 * @property EventPhoto $photo
 *
 * Relations
 * @property User $user
 */
class UserOrderPhoto extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserOrder the static model class
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
		return 'user_order_photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id,order_id,photo_id,quantity','numerical', 'integerOnly'=>true),
			array('size,style', 'length', 'max'=>100),
			array('size,style', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id,order_id,photo_id,quantity,size,style', 'safe', 'on'=>'search'),
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
			'order'		=> array(self::BELONGS_TO, 'Order', 'order_id'),
			'photo'		=> array(self::BELONGS_TO, 'Photo', 'photo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => 'Order ID',
			'photo_id' => 'Photo ID',
			'quantity' => 'Quantity',
			'size' => 'size',
			'style' => 'style',
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
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('photo_id',$this->photo_id,true);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('style',$this->style,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}