<?php

/**
 * This is the model class for table "ticket".
 *
 * The followings are the available columns in table 'ticket':
 * @property string $id
 * @property integer $user_id
 * @property string $code
 * @property string $title
 * @property integer $state
 * @property string $created
 * @property string $updated
 * 
 * @property TicketReply $replies
 * @property Member $user
 */
class Ticket extends YsaActiveRecord
{
	const STATE_CLOSED = -1;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Ticket the static model class
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
		return 'ticket';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('user_id, state', 'numerical', 'integerOnly'=>true),
			array('code', 'unique'),
			array('user_id, state, title, code', 'required'),
			array('code', 'length', 'max'=>20),
			array('title', 'length', 'max'=>255),
			array('created, updated', 'safe'),
			array('id, user_id, code, title, state, created, updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'replies'		=> array(self::HAS_MANY, 'TicketReply', 'ticket_id', 'order' => 'created ASC'),
			'user'			=> array(self::BELONGS_TO, 'Member', 'user_id'),
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
			'code' => 'Code',
			'title' => 'Title',
			'state' => 'State',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function generateCode()
	{
		$this->code = YsaHelpers::short(microtime() + $this->user_id);
	}
	
	public function getStates()
	{
		return array(
			self::STATE_ACTIVE      => 'Active',
			self::STATE_INACTIVE    => 'Inactive',
			self::STATE_CLOSED      => 'Closed',
		);
	}
}