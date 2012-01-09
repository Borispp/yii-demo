<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property string $id
 * @property string $title
 * @property integer $message
 *
 * The followings are the available model relations:
 * @property NotificationUser $notification_user
 */
class Notification extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Notification the static model class
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
		return 'notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'numerical', 'integerOnly'=>true),
			array('title, message', 'required'),
			array('title', 'length', 'max'=>100),
			array('message', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, message', 'safe', 'on'=>'search'),
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
			'notification_user' => array(self::HAS_MANY, 'NotificationUser', 'notification_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'message' => 'Message',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('message',$this->message);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
			));
	}

	/**
	 * Return all notifications to member
	 * @param Member $obMember
	 * @return NotificationUser
	 */
	public function getMemberNotifications(Member $obMember)
	{
		return $this->with(array(
				'notification_user'=>array(
					// we don't want to select posts
					'select'=>false,
					// but want to get only users with published posts
					'joinType'=>'INNER JOIN',
					'condition'=>'notification_user.read=0 and notification_user.user_id=:user_id',
					'params'	=> array(
						':user_id' => $obMember->id
					)
				)
			))->findAll();
	}

	/**
	 * Set state read = 1 to member-specific userNotification or to all userNotifications
	 * @param Member|null $obMember
	 * @return void
	 */
	public function read(Member $obMember = NULL)
	{
		if (is_null($obMember))
		{
			foreach($this->notification_user as $obNotificationUser)
			{
				$obNotificationUser->read = 1;
				$obNotificationUser->save();
			}
			return;
		}
		$obNotificationUser = NotificationUser::model()->findByAttributes(array(
			'read'				=> 0,
			'notification_id'	=> $this->id,
			'user_id'			=> $obMember->id,
		));
		$obNotificationUser->read = 1;
		$obNotificationUser->save();
	}
}