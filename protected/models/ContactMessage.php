<?php

/**
 * This is the model class for table "contact_message".
 *
 * The followings are the available columns in table 'contact_message':
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $message
 * @property string $created
 * @property string $updated
 */
class ContactMessage extends YsaActiveRecord
{
	public $captcha;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return ContactMessage the static model class
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
		return 'contact_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, email', 'length', 'max'=>100),
			array('subject', 'length', 'max'=>200),
			array('email', 'email'),
			array('message, name, email, subject', 'required'),
			array('created, updated, captcha', 'safe'),
//			array('captcha', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'email' => 'Email',
			'subject' => 'Subject',
			'message' => 'Message',
			'created' => 'Created',
			'updated' => 'Updated',
			'captcha' => 'Verification Code',
		);
	}
	
	/**
	 * Send email to admin with new message
	 */
	public function sendEmail()
	{
		/**
		 * TODO send email to admin 
		 */
	}
}