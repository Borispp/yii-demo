<?php
/**
 * This is the model class for table "application_auth".
 *
 * @property integer $id
 * @property string $device_id
 * @property integer $client_id
 * @property integer $app_id
 * @property string $token
 * @property integer $state
 *
 * @property Application $application
 * @property Clien $client
 */
class ClientAuth extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ClientAuth the static model class
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
		return 'client_auth';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id,client_id, app_id', 'state', 'integerOnly'=>true),
			array('client_id, device_id, app_id, token', 'required'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'application'	=> array(self::BELONGS_TO, 'Application', 'app_id'),
			'client'			=> array(self::BELONGS_TO, 'Client', 'client_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'		=> 'ID',
			'device_id'	=> 'Device ID',
			'token'		=> 'Secure Token',
			'state'		=> 'State',
			'app_id'	=> 'Application ID',
			'client_id'	=> 'Client ID'
		);
	}

	/**
	 * Create authorisation token using client and application objects
	 * @param Client $obClient
	 * @param Application $obApplication
	 * @param string $deviceId
	 * @return string
	 */
	protected function _generateToken(Client $obClient, Application $obApplication, $deviceId)
	{
		return md5(md5($obClient->email.$obClient->password.$obApplication->id.$deviceId).Yii::app()->params['salt']);
	}

	/**
	 * Authorization by password.
	 * Returns token or bool FALSE.
	 * @param $email
	 * @param $password
	 * @param $appKey
	 * @param $deviceId
	 * @return bool|string
	 */
	public function authByPassword($email, $password, $appKey, $deviceId)
	{
		$obApplication = Application::model()->findByKey($appKey);
		if (!$obApplication)
			throw new YsaAuthException('No application with such key found');
		$obClient = Client::model()->findByAttributes(array(
			'email'		=> $email,
		));
		if (!$obClient)
			throw new YsaAuthException('No client with such email found');
		if ($obClient->password != $password)
			throw new YsaAuthException('Wrong password');
		if (!$obClient->isActive())
			throw new YsaAuthException('Client is blocked');
		if ($this->authByToken($token = $this->_generateToken($obClient, $obApplication, $deviceId), $appKey, $deviceId))
			return $token;
		
		$model = new ClientAuth();
		$model->token = $token;
		$model->app_id = $obApplication->id;
		$model->client_id = $obClient->id;
		$model->device_id = $deviceId;
		$model->state = 1;
		if ( !$model->save() )
			throw new YsaAuthException('Unable to save ClientAuth');
		
		return $token;
	}

	/**
	 * @param string $token
	 * @param string $appKey
	 * @param string $deviceId
	 * @return bool
	 */
	public function authByToken($token, $appKey, $deviceId)
	{
		$obApplication = Application::model()->findByKey($appKey);
		if (!$obApplication)
			return FALSE;
		return $this->findByAttributes(array(
				'app_id'	=> $obApplication->id,
				'token'		=> $token,
				'device_id'	=> $deviceId,
			));
	}

	/**
	 * @param string $token
	 * @param string $appKey
	 * @param string $deviceId
	 * @return bool
	 */
	public function removeAuth($token, $appKey, $deviceId)
	{
		$obApplication = Application::model()->findByKey($appKey);
		if (!$obApplication)
			return FALSE;
		$obEvent = $this->findByAttributes(array(
				'app_id'	=> $obApplication->id,
				'token'		=> $token,
				'device_id'	=> $deviceId,
			));
		$obEvent->delete();
	}
}