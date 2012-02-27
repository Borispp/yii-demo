<?php

/**
 * This is the model class for table "ipad_notification_relation".
 *
 * The followings are the available columns in table 'ipad_notification_relation':
 * @property string $id
 * @property string $ipad_id
 * @property integer $state
 * @property integer $notification_id
 *
 * The followings are the available model relations:
 * @property Ipad $ipad
 */
class IpadNotificationRelation extends YsaActiveRecord
{
	const STATE_NOT_SENT = 0;
	const STATE_SENT = 1;


	/**
	 * Returns the static model of the specified AR class.
	 * @return IpadNotificationRelation the static model class
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
		return 'ipad_notification_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ipad_id, notification_id', 'required'),
			array('state', 'numerical', 'integerOnly'=>true),
			array('ipad_id, notification_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ipad_id, state, notification_id', 'safe', 'on'=>'search'),
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
			'ipad'         => array(self::BELONGS_TO, 'Ipad', 'ipad_id'),
			'notification' => array(self::BELONGS_TO, 'IpadNotification', 'notification_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ipad_id' => 'Ipad',
			'state' => 'State',
			'notification_id' => 'Push',
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
		$criteria->compare('ipad_id',$this->ipad_id,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('notification_id',$this->notification_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}