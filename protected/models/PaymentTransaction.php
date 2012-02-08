<?php

/**
 * This is the model class for table "payment_transaction".
 *
 * The followings are the available columns in table 'payment_transaction':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property double $summ
 * @property string $type
 * @property string $outer_id
 * @property string $data
 * @property string $notes
 * @property integer $state
 * @property string $created
 * @property string $updated
 * @property string $paid
 *
 * The followings are the available model relations:
 * @property PaymentTransactionApplication[] $paymentTransactionApplications
 * @property PaymentTransactionSubscription[] $paymentTransactionSubscriptions
 */
class PaymentTransaction extends YsaActiveRecord
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
	 * @return PaymentTransaction the static model class
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
		return 'payment_transaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('summ, created', 'required'),
			array('state', 'numerical', 'integerOnly'=>true),
			array('summ', 'numerical'),
			array('name', 'length', 'max'=>500),
			array('description, data, notes, updated, paid', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, summ, type, outer_id, data, notes, state, created, updated, paid', 'safe', 'on'=>'search'),
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
			'paymentTransactionApplications' => array(self::HAS_MANY, 'PaymentTransactionApplication', 'transaction_id'),
			'paymentTransactionSubscriptions' => array(self::HAS_MANY, 'PaymentTransactionSubscription', 'transaction_id'),
		);
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
			'summ' => 'Summ',
			'type' => 'Order Type',
			'outer_id' => 'Outer',
			'data' => 'Data',
			'notes' => 'Notes',
			'state' => 'State',
			'created' => 'Created',
			'updated' => 'Updated',
			'paid' => 'paid',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('summ',$this->summ);
		$criteria->compare('type',$this->type);
		$criteria->compare('outer_id',$this->outer_id);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('paid',$this->paid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getStates()
	{
		return array(
			self::STATE_CREATED		=> 'Created',
			self::STATE_CANCELED	=> 'Canceled',
			self::STATE_PAID		=> 'Paid',
			self::STATE_SENT		=> 'Unpaid',
		);
	}

	public function getItemId()
	{
		if ($this->paymentTransactionApplications)
			return $this->paymentTransactionApplications[0]->application_id;
		if ($this->paymentTransactionSubscriptions)
			return $this->paymentTransactionSubscriptions[0]->subscription_id;
	}

	public function setPaid()
	{
		$this->paid = date('Y-m-d H:i:s');
		$this->state = self::STATE_PAID;
		$this->save();
		$this->processSuccess();
	}

	public function isPaid()
	{
		return $this->paid;
	}

	/**
	 * @return YsaPaymentTransaction
	 */
	protected  function _getTransactionRelationObject()
	{
		if ($this->type == 'application')
		{
			list($applicationRelation) = $this->paymentTransactionApplications;
			return $applicationRelation;
		}
		else
		{
			list($subscriptionRelation) = $this->paymentTransactionSubscriptions;
			return $subscriptionRelation;
		}
	}

	/**
	 * @return void
	 */
	public function processSuccess()
	{
		$this->_getTransactionRelationObject()->processSuccess();
	}

	/**
	 * @return Member
	 */
	public function getMember()
	{
		return $this->_getTransactionRelationObject()->getMember();
	}

	/**
	 * @return array
	 */
	public function getRedirectUrl()
	{
		return $this->_getTransactionRelationObject()->getUrl();
	}
}