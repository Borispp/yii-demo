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
 * @property integer $locked
 * @property integer $filled
 * @property integer $submitted
 * @property integer $ready
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
	 * Approved by website moderator
	 */
	const STATE_SUBMITTED = 2;
	
	/**
	 * Approved by website moderator
	 */
	const STATE_MODERATOR_APPROVED = 3;

	/**
	 * Waiting AppStore Approval
	 */
	const STATE_APPSTORE_WAITING_APPROVAL = 4;

	/**
	 * Application is ready to work
	 */
	const STATE_READY = 5;

	/**
	 * Unapproved by website moderator
	 */
	const STATE_MODERATOR_UNAPROVED = -3;

	/**
	 * Rejected by AppStore
	 */
	const STATE_APPSTORE_REJECTED = -4;
	
	protected $_ticket;
	
	public $default_style;
	
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
	
	protected $_requiredOptions = array(
		'icon',
		'logo',
		'itunes_logo',
		'copyright',
	);
	
	protected $_staticOptions = array(
		'icon',
		'logo',
		'itunes_logo',
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
			array('user_id, state, locked', 'numerical', 'integerOnly'=>true),
			array('appkey, passwd, name', 'length', 'max'=>100),
			array('info, locked, default_style', 'safe'),
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
			'default_style' => 'Default Style',
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
			self::STATE_CREATED						=> 'Created',
			self::STATE_SUBMITTED					=> 'Submitted',
			self::STATE_MODERATOR_APPROVED			=> 'Approved by moderator',
			self::STATE_APPSTORE_WAITING_APPROVAL	=> 'Waiting approval',
			self::STATE_READY						=> 'Ready',
			self::STATE_MODERATOR_UNAPROVED			=> 'Unapproved',
			self::STATE_APPSTORE_REJECTED			=> 'Rejected by Apple',
		);
	}
	
	public function getMemberStates()
	{
		return array(
			self::STATE_CREATED						=> 'Created and waiting for information',
			self::STATE_SUBMITTED					=> 'Submitted for moderator approval',
			self::STATE_MODERATOR_APPROVED			=> 'Approved by moderator',
			self::STATE_APPSTORE_WAITING_APPROVAL	=> 'Sent to AppStore',
			self::STATE_READY						=> 'Ready',
			self::STATE_MODERATOR_UNAPROVED			=> 'Unapproved by moderator',
			self::STATE_APPSTORE_REJECTED			=> 'Rejected by AppStore',
		);
	}
	
	public function memberState()
	{
		$states = $this->getMemberStates();
		return $states[$this->state];
	}

	/**
	 * Check if application needs an application wizard
	 */
	public function filled()
	{
		return $this->filled;
	}
	
	/**
	 * Mark Application as filled
	 * 
	 * @return Application 
	 */
	public function fill()
	{
		$this->filled = 1;
		$this->save();
		
		return $this;
	}
	
	/**
	 * Check if current application is locked from changing static options
	 */
	public function locked()
	{
		return $this->locked;
	}
	
	/**
	 * Lock application to pervert changing static options 
	 * 
	 * @return Application 
	 */
	public function lock()
	{
		$this->locked = 1;
		$this->save();
		return $this;
	}
	
	/**
	 * Check if current application is approved by moderator
	 */
	public function approved()
	{
		return $this->approved == 1;
	}
	
	/**
	 * Approve application by moderator
	 * 
	 * @return Application 
	 */
	public function approve()
	{
		$this->approved = 1;
		$this->save();
		return $this;
	}
	
	/**
	 * Check if current application is approved by moderator
	 */
	public function unapproved()
	{
		return $this->approved == -1;
	}	
	
	/**
	 * Unapprove application by moderator
	 * 
	 * @return Application 
	 */
	public function unapprove()
	{
		$this->approved = -1;
		$this->save();
		return $this;
	}
	
	/**
	 * Submit application for review
	 * 
	 * @return Application 
	 */
	public function submit()
	{
		$this->submitted = 1;
		$this->save();
		return $this;
	}
	
	/**
	 * Check if application is submitted 
	 */
	public function submitted()
	{
		return $this->submitted;
	}
	
	/**
	 * Check if application options are properly filled.
	 * Returns true on success
	 * Retures array with required options on failure
	 *  
	 * @return mixed
	 */
	public function isProperlyFilled()
	{
		$notExists = array();
		foreach ($this->_requiredOptions as $opt) {
			$option = $this->option($opt);
			if (!$option) {
				$notExists[$opt] = $opt;
			}
		}
		return count($notExists) ? $notExists : true; 
	}
	
	/**
	 * Generates error message for Application Wizard
	 * 
	 * @param array $fields
	 * @return string 
	 */
	public function generateFillErrorMsg($fields)
	{
		$msg = 'Please fill these fields correctly to preview and submit application for approval: ';
		
		$_fields = array();
		foreach ($fields as $field) {
			foreach (Yii::app()->params['studio_options'] as $group) {
				foreach ($group as $opt => $values) {
					if ($opt == $field) {
						$_fields[] = $values['label'];
						break;
					}
				}
			}
		}
		
		return $msg . implode(', ', $_fields);
	}
	
	/**
	 * Checks if application has support tickets
	 * 
	 * @return bool
	 */
	public function hasSupport()
	{
		$hasSupport = false;
		switch ($this->state) {
			case self::STATE_MODERATOR_UNAPROVED:
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
	
	/**
	 * Get opened ticket for application
	 * 
	 * @return Ticket
	 */
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
	
	/**
	 * Get required options list to check before submitting application for approval.
	 * 
	 * @return array 
	 */
	public function getRequiredOptions()
	{
		return $this->_requiredOptions;
	}
	
	/**
	 * Get options that are unavailable to change after approval in Locked mode
	 * 
	 * @return array 
	 */
	public function getStaticOptions()
	{
		return $this->_staticOptions;
	}
	
	/**
	 * Get styles list
	 * @return array
	 */
	public function getStyles()
	{
		return Yii::app()->params['studio_options']['styles'];
	}
	
	/**
	 * Fill new application with default styles
	 * 
	 * @return Application 
	 */
	public function fillWithStyle()
	{
		if (!in_array($this->default_style, array_keys($this->getStyles()))) {
			$this->default_style = 'dark';
		}
		
		foreach (Yii::app()->params['default_styles'][$this->default_style] as $key => $value) {
			$this->editOption($key, $value);
		}
		
		$this->editOption('style', $this->default_style);
		
		return $this;
	}
}