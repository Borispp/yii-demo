<?php
/**
 * This is the model class for table "application_auth".
 *
 * The followings are the available columns in table 'option_group':
`type` set('client','proofing') NOT NULL DEFAULT 'client',

 * @property integer $id
 * @property string $device_id
 * @property integer $app_id
 * @property string $token
 * @property integer $state
 * @property string $type
 */
class ApplicationAuth extends YsaActiveRecord
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
		return 'application_auth';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array();
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		//		return array(
		//			array('rank, hidden', 'numerical', 'integerOnly'=>true),
		//			array('title, slug', 'length', 'max'=>100),
		//			array('slug', 'unique'),
		//			array('slug, title', 'required'),
		//		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'application'	=> array(self::HAS_ONE, 'Application', 'app_id')
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
			'type'		=> 'Auth type'
		);
	}

	/**
	 * @param string $password
	 * @param integer $deviceId
	 * @param integer $appId
	 * @return string
	 */
	protected function _generateToken($password, $deviceId, Application $obApplication)
	{
		return md5(md5($password.$deviceId.$obApplication->id).Yii::app()->params['salt']);
	}

	/**
	 * Authorization by password.
	 * Returns token or bool FALSE.
	 *
	 * @param string $password
	 * @param string $appKey
	 * @param string $deviceId
	 * @param string $type
	 * @return mixed
	 */
	public function authByPassword($password, $appKey, $deviceId, $type)
	{
		$obApplication = Application::model()->findByKey($appKey);
		if (!$obApplication)
			return FALSE;
		if ($this->authByToken($token = $this->_generateToken($password, $deviceId, $obApplication), $appKey, $deviceId, $type))
			return $token;
		if ($obApplication->passwd != $password)
			return FALSE;
		$model = new ApplicationAuth();
		$model->token = $token;
		$model->app_id = $obApplication->id;
		$model->device_id = $deviceId;
		$model->state = 1;
		$model->type = $type;
		$model->save();
		return $token;
	}

	/**
	 * @param string $token
	 * @param string $appKey
	 * @param string $deviceId
	 * @param string $type
	 * @return bool
	 */
	public function authByToken($token, $appKey, $deviceId, $type)
	{
		$obApplication = Application::model()->findByKey($appKey);
		if (!$obApplication)
			return FALSE;
		return $this->findByAttributes(array(
			'app_id'	=> $obApplication->id,
			'token'		=> $token,
			'device_id'	=> $deviceId,
			'type'		=> $type,
		));
	}

}