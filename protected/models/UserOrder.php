<?php

/**
 * This is the model class for table "user_order".
 *
 * The followings are the available columns in table 'user_order':
 * @property string $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $country
 * @property string $state
 * @property string $zip
 * @property string $phone_day
 * @property string $phone_evening
 * @property string $phone_mobile
 * @property string $fax
 * @property string $email
 * @property string $notes
 * 
 * Relations
 * @property User $user
 */
class UserOrder extends YsaActiveRecord
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
		return 'user_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name, city, country, state, zip, phone_day, phone_evening, phone_mobile, fax, email', 'length', 'max'=>50),
			array('address1, address2', 'length', 'max'=>255),
			array('notes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, first_name, last_name, address1, address2, city, country, state, zip, phone_day, phone_evening, phone_mobile, fax, email, notes', 'safe', 'on'=>'search'),
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
			'user'		=> array(self::BELONGS_TO, 'User', 'user_id'),
			'photos'	=> array(self::HAS_MANY, 'UserOrderPhoto', 'order_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'address1' => 'Address1',
			'address2' => 'Address2',
			'city' => 'City',
			'country' => 'Country',
			'state' => 'State',
			'zip' => 'Zip',
			'phone_day' => 'Phone Day',
			'phone_evening' => 'Phone Evening',
			'phone_mobile' => 'Phone Mobile',
			'fax' => 'Fax',
			'email' => 'Email',
			'notes' => 'Notes',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('address1',$this->address1,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('phone_day',$this->phone_day,true);
		$criteria->compare('phone_evening',$this->phone_evening,true);
		$criteria->compare('phone_mobile',$this->phone_mobile,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('notes',$this->notes,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}