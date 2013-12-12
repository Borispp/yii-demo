<?php

/**
 * This is the model class for table "payment_transaction_subscription".
 *
 * The followings are the available columns in table 'payment_transaction_subscription':
 * @property string $id
 * @property string $subscription_id
 * @property string $transaction_id
 *
 * The followings are the available model relations:
 * @property UserSubscription $subscription
 * @property PaymentTransaction $transaction
 */
class PaymentTransactionSubscription extends YsaActiveRecord implements YsaPaymentTransaction
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PaymentTransactionSubscription the static model class
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
		return 'payment_transaction_subscription';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subscription_id', 'length', 'max'=>11),
			array('transaction_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, subscription_id, transaction_id', 'safe', 'on'=>'search'),
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
			'subscription' => array(self::BELONGS_TO, 'UserSubscription', 'subscription_id'),
			'transaction' => array(self::BELONGS_TO, 'PaymentTransaction', 'transaction_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'subscription_id' => 'Subscription',
			'transaction_id' => 'Transaction',
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
		$criteria->compare('subscription_id',$this->subscription_id,true);
		$criteria->compare('transaction_id',$this->transaction_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function processSuccess()
	{
		$this->subscription->activate();
	}

	public function getUrl()
	{
		return array('subscription/list/');
	}

	/**
	 * @return Member
	 */
	public function getMember()
	{
		return $this->subscription->Member;
	}
}