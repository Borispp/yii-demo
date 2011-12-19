<?php

/**
 * This is the model class for table "user_subscription".
 *
 * The followings are the available columns in table 'user_subscription':
 * @property string $id
 * @property integer $user_id
 * @property integer $membership_id
 * @property string $start_date
 * @property string $update_date
 * @property string $expiry_date
 * @property integer $discount_id
 * @property integer $state
 */
class UserSubscription extends CActiveRecord
{
	public $discount;
	/**
	 * Created by member
	 */
	const STATE_ENABLED = 0;

	/**
	 * Sent to paypal
	 */
	const STATE_DISABLED = 1;

	/**
	 * Returns the static model of the specified AR class.
	 * @return UserSubscription the static model class
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
		return 'user_subscription';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, membership_id', 'required'),
			array('user_id, membership_id, discount_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, membership_id, start_date, update_date, expiry_date, discount_id', 'safe', 'on'=>'search'),
			array('discount', 'validateDiscount',)
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function validateDiscount()
	{
		if (!$this->hasErrors()) {
			if (!empty($this->discount))
			{
				$obDiscount = Discount::model()->findByCode($this->discount);
				if (!$obDiscount)
					return $this->addError("discount", "No discount code found");
				if (!$obDiscount->canBeUsed($this->Membership))
					return $this->addError("discount", 'This discount code can\'t affect price of selected membership');
				$this->discount_id = $obDiscount->id;
			}
		}
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'			=> 'ID',
			'user_id'		=> 'User',
			'membership_id'	=> 'Membership',
			'start_date'	=> 'Start Date',
			'update_date'	=> 'Update Date',
			'expiry_date'	=> 'Expiry Date',
			'discount_id'	=> 'Discount',
			'state'			=> 'State',
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
		$criteria->compare('membership_id',$this->membership_id);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('update_date',$this->update_date,true);
		$criteria->compare('expiry_date',$this->expiry_date,true);
		$criteria->compare('discount_id',$this->discount_id);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
			));
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


}