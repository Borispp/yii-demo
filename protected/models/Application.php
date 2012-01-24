<?php

/**
 * This is the model class for table "application".
 *
 * The followings are the available columns in table 'application':
 * @property string $id
 * @property integer $user_id
 * @property string $appkey
 * @property string $passwd
 * @property integer $state
 * @property string $name
 * @property string $info
 * @property Member $user
 * @property ApplicationOption $application
 */
class Application extends YsaActiveRecord
{
	/**
	 * Created by member
	 */
	const STATE_CREATED = 1;

	/**
	 * Filled with information
	 */
	const STATE_FILLED = 2;
	
	/**
	 * Approved by website moderator
	 */
	const STATE_APPROVED = 3;

	/**
	 * Waiting AppStore Approval
	 */
	const STATE_WAITING_APPROVAL = 4;

	/**
	 * Application is ready to work
	 */
	const STATE_READY = 5;

	/**
	 * Unapproved by website moderator
	 */
	const STATE_UNAPROVED = -3;

	/**
	 * Rejected by AppStore
	 */
	const STATE_REJECTED = -5;
	
	protected $_ticket;
	
	protected $_steps = array(
		'logo' => array(
			'position' => 1,
			'title' => 'Logo',
			'title_annotation' => 'Logo &amp; backgrounds',
			'header' => 'Upload Your Logo',
		),
		'colors' => array(
			'position' => 2,
			'title' => 'Colors',
			'title_annotation' => 'Colors &amp; images',
			'header' => 'Set Your Colors',
		),
		'fonts' => array(
			'position' => 3,
			'title' => 'Fonts',
			'title_annotation' => 'Fonts &amp; colors',
			'header' => 'Choose Your Fonts',
		),
		'copyrights' => array(
			'position' => 4,
			'title' => 'Copyrights',
			'title_annotation' => 'Copyrights',
			'header' => 'Set Your Copyrights',
		),
		'submit' => array(
			'position' => 5,
			'title' => 'Submit',
			'title_annotation' => 'It\'s all done!',
			'header' => 'Submit Your Application',
		),
		
	);

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'application';
	}

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, appkey, passwd, name', 'required'),
			array('user_id, appkey, name', 'unique'),
			array('user_id, state', 'numerical', 'integerOnly'=>true),
			array('appkey, passwd, name', 'length', 'max'=>100),
			array('info', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, state, name', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'user'        => array(self::BELONGS_TO, 'Member', 'user_id'),
			'options'	  => array(self::HAS_MANY, 'ApplicationOption', 'app_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'appkey' => 'Appkey',
			'passwd' => 'Password',
			'state' => 'State',
			'name' => 'Name',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('state',$this->state);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function findByMember($memberId)
	{
		return $this->findByAttributes(array(
				'user_id'   => $memberId,
			));
	}
	
	public function generatePasswd()
	{
		$this->passwd = YsaHelpers::genRandomString();
	}

	public function generateAppKey()
	{
		$this->appkey = YsaHelpers::encrypt(microtime() . YsaHelpers::genRandomString(20) . Yii::app()->params['salt']);
	}

	public function findByKey($key)
	{
		return $this->findByAttributes(array(
				'appkey'	=> $key
			));
	}

	public function getStates()
	{
		return array(
			self::STATE_CREATED				=> 'Created',
			self::STATE_FILLED				=> 'Filled',
			self::STATE_APPROVED			=> 'Approved',
			self::STATE_WAITING_APPROVAL	=> 'Waiting approval',
			self::STATE_READY				=> 'Ready',
			self::STATE_UNAPROVED			=> 'Unapproved',
			self::STATE_REJECTED			=> 'Rejected by Apple',
		);
	}
	
	public function getMemberStates()
	{
		return array(
			self::STATE_CREATED				=> 'Created and Waiting for Information',
			self::STATE_FILLED				=> 'Filled & Waiting for Administrator Approval',
			self::STATE_APPROVED			=> 'Approved and Sent to Apple',
			self::STATE_WAITING_APPROVAL	=> 'Waiting for Apple Approval',
			self::STATE_READY				=> 'Ready',
			self::STATE_UNAPROVED			=> 'Unapproved',
			self::STATE_REJECTED			=> 'Rejected by Apple',
		);
	}
	
	public function memberState()
	{
		$states = $this->getMemberStates();
		return $states[$this->state];
	}

	/**
	 * TODO
	 *
	 * Check if application needs an application wizard
	 */
	public function filled()
	{
		$filled = false;
		switch ($this->state) {
			case self::STATE_CREATED:
				$filled = false;
				break;
			default:
				$filled = true;
				break;
		}

		return $filled;
	}
	
	public function hasSupport()
	{
		$hasSupport = false;
		switch ($this->state) {
			case self::STATE_UNAPROVED:
				$hasSupport = true;
				break;
			default:
				$hasSupport = false;
				break;
		}

		return $hasSupport;
	}


	public function getUploadDir()
	{
		$dir = rtrim(Yii::getPathOfAlias('webroot.images.apps'), '/');

		$dir .= DIRECTORY_SEPARATOR . $this->id;

		if (!is_dir($dir)) {
			mkdir($dir);
			chmod($dir, 0777);
		}

		return $dir;
	}

	public function getUploadUrl()
	{
		$url = Yii::app()->getBaseUrl(true) . '/images/apps/' . $this->id;

		return $url;
	}



	public function editOption($name, $value, $type = null)
	{
		$option = ApplicationOption::model()->findByAttributes(array(
			'name'   => $name,
			'app_id' => $this->id,
		));

		if (null === $type) {
			$type = Option::TYPE_TEXT;
		}

		if (null === $option) {
			$option = new ApplicationOption();
			$option->name = $name;
			$option->app_id = $this->id;
		}
		
		if ($value instanceof CUploadedFile) {
			// remove old image if exists
			$val = $option->value();
			
			if (isset($val['path'])) {
				if (is_file($val['path'])) {
					unlink($val['path']);
				}
			}
			
			$ext = Yii::app()->params['application'][$name]['ext'];
			if (!$ext) {
				$ext = 'png';
			}
			
			$imageName = YsaHelpers::encrypt(microtime() . $value->tempName) . '.' . $ext;

			$imageSaveDir = $this->getUploadDir() . DIRECTORY_SEPARATOR . $imageName;
			$imageSaveUrl = $this->getUploadUrl() . '/' . $imageName;

			$image = new YsaImage($value->tempName);
			
			// resize
			if (isset(Yii::app()->params['application'][$name]['width']) && isset(Yii::app()->params['application'][$name]['height'])) {
				$width = Yii::app()->params['application'][$name]['width'];
				$height = Yii::app()->params['application'][$name]['height'];
				
				$image->auto_crop($width, $height);
			} else {
				$width = $image->width;
				$height = $image->height;
			}
			
			$image->save($imageSaveDir);

			$value = array(
				'width'     => $width,
				'height'    => $height,
				'type'      => $image->type,
				'ext'       => $ext,
				'mime'      => $image->mime,
				'path'      => $imageSaveDir,
				'url'       => $imageSaveUrl,
			);
		}

		if (is_array($value)) {
			$value = serialize($value);
		}

		$option->value = $value;

		$option->save();

		return true;
	}
	
	/**
	 * Remove application option
	 * 
	 * @param string $name
	 * @return bool
	 */
	public function deleteOption($name)
	{
		$option = ApplicationOption::model()->findByAttributes(array(
			'name'   => $name,
			'app_id' => $this->id,
		));
		
		if (null !== $option) {
			$option->delete();
		}
		
		return true;
	}

	/**
	 * Find specific option value for application
	 * @param string $name
	 * @return object
	 */
	public function option($name)
	{
		$option = ApplicationOption::model()->findByAttributes(array(
				'name'   => $name,
				'app_id' => $this->id,
			));

		return $option ? $option->value() : null;
	}
	
	/**
	 * Get names of available images for application
	 * 
	 * @return array
	 */
	public function getAvailableImages()
	{
		return array_keys(Yii::app()->params['application']);
	}
	
	/**
	 * Get steps for Application Wizard
	 * 
	 * @return array
	 */
	public function wizardSteps()
	{
		return $this->_steps;
	}
	
	/**
	 * Check if current Wizard Step exists
	 * 
	 * @param string $step
	 * @return bool
	 */
	public function filterWizardStep($step)
	{
		return in_array($step, array_keys($this->wizardSteps()));
	}
	
	public function ticket()
	{
		if (!$this->hasSupport()) {
			return null;
		}
		
		if (null === $this->_ticket) {
			$this->_ticket = Ticket::model()->find('user_id=:user_id AND state=:state', array(
				'user_id' => $this->user->id,
				'state'   => Ticket::STATE_ACTIVE
			));
		}
		
		return $this->_ticket;
	}
}