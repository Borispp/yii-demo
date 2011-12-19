<?php

/**
 * This is the model class for table "user_transaction".
 *
 * The followings are the available columns in table 'user_transaction':
 * @property string $id
 * @property integer $user_subscription_id
 * @property string $data
 * @property string $notes
 * @property integer $state
 * @property string $created
 * @property string $payed
 */
class UserTransaction extends CActiveRecord
{
	/**
	 * Created by member
	 */
	const STATE_CREATED = 0;

	/**
	 * Sent to paypal
	 */
	const STATE_SENT = 1;

	/**
	 * Approved by website moderator
	 */
	const STATE_PAID = 2;

	/**
	 * Waiting AppStore Approval
	 */
	const STATE_CANCELED = -1;

	/**
	 * Returns the static model of the specified AR class.
	 * @return UserTransaction the static model class
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
		return 'user_transaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_subscription_id, created', 'required'),
			array('user_subscription_id, state', 'numerical', 'integerOnly'=>true),
			array('data, notes, payed', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_subscription_id, data, notes, state, created, payed', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_subscription_id' => 'User Subscription',
			'data' => 'Data',
			'notes' => 'Notes',
			'state' => 'State',
			'created' => 'Created',
			'payed' => 'Payed',
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
		$criteria->compare('user_subscription_id',$this->user_subscription_id);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('payed',$this->payed,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}