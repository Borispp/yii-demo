<?php

/**
 * This is the model class for table "ticket_reply".
 *
 * The followings are the available columns in table 'ticket_reply':
 * @property string $id
 * @property integer $ticket_id
 * @property integer $reply_by
 * @property string $message
 * @property string $created
 * @property string $updated
 *
 * @property Ticket $ticket
 * @property User $replier
 */
class TicketReply extends YsaActiveRecord implements YsaNotificationMessage
{
	/**
	 * @var integer
	 */
	public $notify;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return TicketReply the static model class
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
		return 'ticket_reply';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ticket_id, reply_by', 'numerical', 'integerOnly'=>true),
			array('ticket_id, reply_by, message', 'required'),
			array('notify, created, notify, updated', 'safe'),
			array('id, ticket_id, reply_by, message, created, updated', 'safe', 'on'=>'search'),
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
			'ticket' => array(self::BELONGS_TO, 'Ticket', 'ticket_id'),
			'replier' => array(self::BELONGS_TO, 'User', 'reply_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ticket_id' => 'Ticket',
			'reply_by' => 'Reply By',
			'message' => 'Message',
			'created' => 'Created',
			'updated' => 'Updated',
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
		$criteria->compare('ticket_id',$this->ticket_id);
		$criteria->compare('reply_by',$this->reply_by);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function message()
	{
		return nl2br($this->message);
	}

	public function getNotificationMessage()
	{
		return $this->message;
	}

	public function getNotificationTitle()
	{
		return $this->title;
	}

}