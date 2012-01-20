<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $email
 * @property string $role
 * @property string $first_name
 * @property string $last_name
 * @property string $activation_key
 * @property string $password
 * @property integer $state
 * @property string $created
 * @property string $updated
 * @property string $last_login
 * @property string $last_login_ip
 *
 * Relations
 * @property UserOption $option
 * @property Application $application
 * @property Event $event
 * @property Client $client
 * @property Studio $studio
 * @property UserOrder $orders
 * @property Event $public_events
 * @property Event $portfolio_events
 * @property Event $proof_events
 */
class User extends YsaActiveRecord
{
	const ROLE_ADMIN = 'admin';
	const ROLE_MEMBER = 'member';

	const STATE_BANNED = -1;

	protected $_studio;

	protected $_options;

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state', 'numerical', 'integerOnly'=>true),
			array('email, password', 'length', 'max'=>100),
			array('email', 'unique'),
			array('email', 'email'),
			array('role', 'length', 'max'=>6),
			array('last_login_ip', 'length', 'max'=>20),
			array('created, updated, last_login', 'safe'),
			array('email, state, role, first_name, last_name, password', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, role, state', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'options'		=> array(self::HAS_MANY, 'UserOption', 'user_id'),
			'application'	=> array(self::HAS_ONE, 'Application', 'user_id'),
			'studio'		=> array(self::HAS_ONE, 'Studio', 'user_id'),
			'orders'		=> array(self::HAS_MANY, 'UserOrder', 'user_id'),
			
			'events'			=> array(self::HAS_MANY, 'Event', 'user_id', 'order' => 'id DESC'),
			'client'			=> array(self::HAS_MANY, 'Client', 'user_id', 'order' => 'id DESC'),
			'proof_events'		=> array(self::HAS_MANY, 'Event', 'user_id', 'order' => 'id DESC', 'condition' => 'type=:type', 'params' => array('type' => Event::TYPE_PROOF)),
			'portfolio_events'	=> array(self::HAS_MANY, 'Event', 'user_id', 'order' => 'id DESC', 'condition' => 'type=:type', 'params' => array('type' => Event::TYPE_PORTFOLIO)),
			'public_events'		=> array(self::HAS_MANY, 'Event', 'user_id', 'order' => 'id DESC', 'condition' => 'type=:type', 'params' => array('type' => Event::TYPE_PUBLIC)),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'role' => 'Role',
			'password' => 'Password',
			'state' => 'State',
			'created' => 'Created',
			'updated' => 'Updated',
			'last_login' => 'Last Login',
			'last_login_ip' => 'Last Login Ip',
		);
	}

	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function generateActivationKey()
	{
		$this->activation_key = YsaHelpers::encrypt(microtime() . YsaHelpers::genRandomString(20));
	}

	public function encryptPassword()
	{
		$this->password = YsaHelpers::encrypt($this->password);
	}

	/**
	 * @return boolean whether the saving succeeds
	 */
	public function activate()
	{
		$this->state = self::STATE_ACTIVE;
		return $this->save( false );
	}

	/**
	 * @return boolean whether the saving succeeds
	 */
	public function ban()
	{
		$this->state = self::STATE_BANNED;
		return $this->save( false );
	}

	public function name()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	public function getStates()
	{
		return array(
			self::STATE_ACTIVE      => 'Active',
			self::STATE_INACTIVE    => 'Inactive',
			self::STATE_BANNED      => 'Banned',
		);
	}

	public function state()
	{
		if ($this->state == self::STATE_BANNED) {
			return 'Banned';
		} else {
			return parent::state();
		}
	}

	public function getActivationLink()
	{
		return Yii::app()->createAbsoluteUrl('/activate/k/' . $this->activation_key);
	}

	public function getRecoveryLink()
	{
		return Yii::app()->createAbsoluteUrl('/recovery/k/' . $this->activation_key);
	}

	/**
	 * Add/update option wrapper for UserOption
	 * @param string $name
	 * @param mixed $value
	 * @return User
	 */
	public function editOption($name, $value)
	{
		UserOption::model()->editOption($name, $value, $this->id);
		if (isset ($this->_options[$name])) {
			unset ($this->_options[$name]);
		}
		return $this;
	}

	/**
	 * Delete option wrapper for UserOption
	 * @param string $name
	 * @return User
	 */
	public function deleteOption($name)
	{
		UserOption::model()->deleteOption($name, $this->id);
		if (isset($this->_options[$name])) {
			unset ($this->_options[$name]);
		}
		return $this;
	}

	/**
	 * Delete many options in safe transactional manner
	 * 
	 * @param array $options
	 * @return boolean
	 */
	public function deleteOptions( array $options )
	{
		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			foreach ( $options as $option )
			{
				if ( ! $this->deleteOption( $option, $this->id ) )
					throw new CException();
			}
			$transaction->commit();
			return true;
		}
		catch ( CException $e )
		{
			$transaction->rollBack();
			return false;
		}
	}
	
	/**
	 * Get option wrapper for UserOption
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function option($name, $default = '')
	{
		if (!isset($this->_options[$name])) {
			$this->_options[$name] = UserOption::model()->getOption($name, $default, $this->id);
		}

		return $this->_options[$name];
	}

	public function sendShootQ($data)
	{
		$enabled = $this->option('shootq_enabled');

		if (!$enabled) {
			return false;
		}

		$abbr = $this->option('shootq_abbr');
		$key = $this->option('shootq_key');

		if (!$abbr || !$key) {
			return false;
		}

		$data['api_key'] = $key;

		$url = "https://app.shootq.com/api/{$abbr}/leads";

		$data = CJSON::encode($data);

		Yii::import('ext.httpclient.*');
		Yii::import('ext.httpclient.adapter.*');
		$client = new EHttpClient($url, array(
				'maxredirects' => 0,
				'timeout'      => 30,
				'adapter'	   => 'EHttpClientAdapterCurl',
			));

		$client->setRawData($data, 'application/json');
		
		// FINISH SHOOTQ INTEGRATION




	}


	//function flotheme_shootq_send($data)
	//{
	//    $api = flotheme_get_option('shootq_api');
	//    $abbr = flotheme_get_option('shootq_abbr');
	//
	//    $data['api_key'] = $api;
	//
	//    $url = "https://app.shootq.com/api/{$abbr}/leads";
	//
	//    $json_data = json_encode($data);
	//
	//    /* send this data to ShootQ via the API */
	//    $ch = curl_init();
	//    curl_setopt($ch, CURLOPT_URL, $url);
	//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//    curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
	//    curl_setopt($ch, CURLOPT_POST, TRUE);
	//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	//
	//    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
	//
	//    $response_json = curl_exec($ch);
	//    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	//    $response = json_decode($response_json);
	//
	//    if (curl_errno($ch) == 0 && $httpcode == 200) {
	//        curl_close($ch);
	//    } else {
	//        curl_close($ch);
	//        throw new Exception('Cannot send mail. Please check all fields');
	//    }
	//
	//    return true;
	//}

}