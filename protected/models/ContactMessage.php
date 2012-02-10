<?php

/**
 * This is the model class for table "contact_message".
 *
 * The followings are the available columns in table 'contact_message':
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $message
 * @property string $created
 * @property string $updated
 * @property string $studio_name
 * @property string $studio_website
 * @property string $phone_number
 */
class ContactMessage extends YsaActiveRecord
{
	public $captcha;
	
	public $subscribe;
	
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
			array('name, email, studio_name, studio_website, phone_number', 'length', 'max'=>100),
			array('email', 'email'),
			array('message, name, email', 'required'),
			array('subscribe', 'boolean'),
			array('created, updated, captcha, studio_website, phone_number, studio_name, subscribe', 'safe'),
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
			'message' => 'Message',
			'created' => 'Created',
			'updated' => 'Updated',
			'captcha' => 'Verification Code',
			'subscribe' => 'Subscribe to the YSA Newsletter',
		);
	}
	
	/**
	 * Send email to admin with new message
	 */
	public function sendEmail()
	{
		$admin = Admin::model()->findByPk(1);
		
		Email::model()->send(
			array($admin->email, $admin->name()), 
			'contact_admin',
			array(
				'name'			=> $this->name,
				'email'			=> $this->email,
				'date'			=> Yii::app()->dateFormatter->formatDateTime(time()),
				'studio_name'	=> $this->studio_name,
				'studio_website'=> $this->studio_website,
				'phone'			=> $this->phone_number,
				'message'		=> $this->message(),
			)
		);
	}
	
	public function message()
	{
		return nl2br($this->message);
	}
}