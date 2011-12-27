<?php
/**
 * This is the model class for table "application_auth".
 *
 * @property integer $id
 * @property string $device_id
 * @property integer $event_id
 * @property integer $app_id
 * @property string $token
 * @property integer $state
 */
class EventAuth extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OptionGroup the static model class
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
		return 'event_auth';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id,event_id, app_id', 'state', 'integerOnly'=>true),
			array('event_id, device_id, app_id, token', 'required'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'application'	=> array(self::BELONGS_TO, 'Application', 'app_id'),
			'event'			=> array(self::BELONGS_TO, 'Event', 'event_id')
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
			'event_id'	=> 'Event ID'
		);
	}

	/**
	 * @param string $password
	 * @param integer $deviceId
	 * @param integer $appId
	 * @return string
	 */
	protected function _generateToken($password, $deviceId, Application $obApplication, $eventId)
	{
		return md5(md5($password.$deviceId.$obApplication->id.$eventId).Yii::app()->params['salt']);
	}

	/**
	 * Authorization by password.
	 * Returns token or bool FALSE.
	 *
	 * @param string $password
	 * @param string $appKey
	 * @param string $eventId
	 * @param string $deviceId
	 * @param string $type
	 * @return mixed
	 */
	public function authByPassword($password, $appKey, $eventId, $deviceId)
	{
		$obApplication = Application::model()->findByKey($appKey);
		$obEvent = Event::model()->findByPk($eventId);
		if (!$obEvent)
			return FALSE;
		if (!$obApplication)
			return FALSE;
		if ($this->authByToken($token = $this->_generateToken($password, $deviceId, $obApplication, $eventId), $appKey, $eventId, $deviceId))
			return $token;
		if ($obEvent->passwd != $password)
			return FALSE;
		$model = new EventAuth();
		$model->token = $token;
		$model->app_id = $obApplication->id;
		$model->event_id = $obEvent->id;
		$model->device_id = $deviceId;
		$model->state = 1;
		$model->save();
		return $token;
	}

	/**
	 * @param string $token
	 * @param string $appKey
	 * @param integer $eventId
	 * @param string $deviceId
	 * @return bool
	 */
	public function authByToken($token, $appKey, $eventId, $deviceId)
	{
		$obApplication = Application::model()->findByKey($appKey);
		$obEvent = Event::model()->findByPk($eventId);
		if (!$obApplication)
			return FALSE;
		if (!$obEvent)
			return FALSE;
		return $this->findByAttributes(array(
				'app_id'	=> $obApplication->id,
				'token'		=> $token,
				'device_id'	=> $deviceId,
				'event_id'	=> $eventId
			));
	}

	/**
	 * @param string $token
	 * @param string $appKey
	 * @param integer $eventId
	 * @param string $deviceId
	 * @return bool
	 */
	public function removeAuth($token, $appKey, $eventId, $deviceId)
	{
		$obApplication = Application::model()->findByKey($appKey);
		$obEvent = Event::model()->findByPk($eventId);
		if (!$obApplication)
			return FALSE;
		if (!$obEvent)
			return FALSE;
		$obEvent = $this->findByAttributes(array(
				'app_id'	=> $obApplication->id,
				'token'		=> $token,
				'device_id'	=> $deviceId,
				'event_id'	=> $eventId
			));
		$obEvent->delete();
	}

}