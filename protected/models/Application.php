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
 * @property integer $paid
 * @property integer $submitted
 * @property integer $ready
 * @property Member $user
 * @property ApplicationOption $application
 * @property ApplicationHistoryLog $history_log
 */
class Application extends YsaActiveRecord
{
//	/**
//	 * Created by member
//	 */
//	const STATE_CREATED = 1;
//
//	/**
//	 * Approved by website moderator
//	 */
//	const STATE_SUBMITTED = 2;
//	
//	/**
//	 * Approved by website moderator
//	 */
//	const STATE_MODERATOR_APPROVED = 3;
//
//	/**
//	 * Waiting AppStore Approval
//	 */
//	const STATE_APPSTORE_WAITING_APPROVAL = 4;
//
//	/**
//	 * Application is ready to work
//	 */
//	const STATE_READY = 5;
//
//	/**
//	 * Unapproved by website moderator
//	 */
//	const STATE_MODERATOR_UNAPROVED = -3;
//
//	/**
//	 * Rejected by AppStore
//	 */
//	const STATE_APPSTORE_REJECTED = -4;
	
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
			array('user_id, state, locked, filled, submitted, ready, paid', 'numerical', 'integerOnly'=>true),
			array('appkey, passwd, name', 'length', 'max'=>100),
			array('info, locked, filled, submitted, ready, default_style, paid', 'safe'),
			array('id, user_id, state, name', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'user'			=> array(self::BELONGS_TO, 'Member', 'user_id'),
			'options'		=> array(self::HAS_MANY, 'ApplicationOption', 'app_id'),
			'history_log'	=> array(self::HAS_MANY, 'ApplicationHistoryLog', 'app_id'),
			'transaction'	=> array(self::MANY_MANY, 'PaymentTransaction', 'payment_transaction_application(application_id, transaction_id)'),
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

//	public function getStates()
//	{
//		return array(
//			self::STATE_CREATED						=> 'Created',
//			self::STATE_SUBMITTED					=> 'Submitted',
//			self::STATE_MODERATOR_APPROVED			=> 'Approved by moderator',
//			self::STATE_APPSTORE_WAITING_APPROVAL	=> 'Waiting approval',
//			self::STATE_READY						=> 'Ready',
//			self::STATE_MODERATOR_UNAPROVED			=> 'Unapproved',
//			self::STATE_APPSTORE_REJECTED			=> 'Rejected by Apple',
//		);
//	}
	
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
	public function fill($log = true)
	{
		$this->filled = 1;
		$this->save();
		if ($log) {
			$this->log('fill');
		}
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
	public function lock($log = true)
	{
		$this->locked = 1;
		$this->save();
		if ($log) {
			$this->log('lock');
		}
		return $this;
	}
	
	/**
	 * Unlock application
	 * 
	 * @return Application 
	 */
	public function unlock($log = true)
	{
		$this->locked = 0;
		$this->save();
		if ($log) {
			$this->log('unlock');
		}
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
	public function approve($log = true)
	{
		$this->approved = 1;
		$this->save();
		if ($log) {
			$this->log('approve');
		}
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
	public function unapprove($log = true)
	{
		$this->approved = -1;
		$this->save();
		if ($log) {
			$this->log('unapprove');
		}
		return $this;
	}
	
	/**
	 * Submit application for review
	 * 
	 * @return Application 
	 */
	public function submit($log = true)
	{
		$this->submitted = 1;
		$this->save();
		if ($log) {
			$this->log('submit');
		}
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
	 * Mark application as ready and submit to AppStore
	 * 
	 * @return Application 
	 */
	public function ready($log = true)
	{
		$this->ready = 1;
		$this->save();
		if ($log) {
			$this->log('ready');
		}
		return $this;
	}
	
	/**
	 * Check if application is ready 
	 */
	public function isReady()
	{
		return $this->ready == 1;
	}
	
	/**
	 * Application was rejected by AppStore
	 * 
	 * @return Application 
	 */
	public function reject($log = true)
	{
		$this->ready = -1;
		$this->save();
		if ($log) {
			$this->log('reject');
		}
		return $this;
	}
	
	/**
	 * Check if application was rejected by AppStore
	 */
	public function rejected()
	{
		return $this->ready == -1;
	}
	
	/**
	 * Application was approved by AppStore
	 * 
	 * @return Application 
	 */
	public function run($log = true)
	{
		$this->ready = 2;
		$this->save();
		if ($log) {
			$this->log('run');
		}
		return $this;
	}
	
	/**
	 * Check if application is running 
	 */
	public function running()
	{
		return $this->ready == 2;
	}
	
	/**
	 * Restart application submit process and start from the scratch.
	 * @return Application 
	 */
	public function restart($log = true)
	{
		$this->approved = 0;
		$this->locked = 0;
		$this->ready = 0;
		$this->save();
		
		if ($log) {
			$this->log('restart');
		}
		
		return $this;
	}
	
	public function log($action = '')
	{
		$userId = isset($this->user->id) ? $this->user->id : 0;
		
		$logger = new ApplicationHistoryLog();
		$logger->setAttributes(array(
			'app_id' => $this->id,
			'type'	 => $this->numStatus(),
			'created'=> date(self::FORMAT_DATETIME),
			'user_id'=> $userId,
			'action' => $action,
		));
		
		return $this;
	}
	
	
	public function numStatus()
	{
		return $this->filled . $this->submitted . $this->locked . $this->approved . $this->ready;
	}
	
	public function status()
	{
		$s = '';
		switch ($this->numStatus()) {
			case '00000':
				$s = 'newly-created';
				break;
			case '10000':
				$s = 'filled';
				break;
			case '11000':
				$s = 'submitted';
				break;
			case '11100':
				$s = 'locked';
				break;
			case '11110':
				$s = 'approved';
				break;
			case '11111':
				$s = 'appstore';
				break;
			case '11112':
				$s = 'running';
				break;
			case '1111-1':
				$s = 'rejected';
				break;
			case '111-10':
			case '110-10':
				$s = 'unapproved';
				break;
		}
		
		return $s;
		
	}
	
	public function statusLabel()
	{
		$label = '';
		switch ($this->status()) {
			case 'newly-created':
				$label = 'Application is up.';
				break;
			case 'filled':
				$label = 'Application is filled up.';
				break;
			case 'submitted':
				$label = 'Application has been successfully submitted.';
				break;
			case 'locked':
				$label = 'Application has been locked to pervert requred fields changes.';
				break;
			case 'approved':
				$label = 'Application has been successfully approved by moderators.';
				break;
			case 'appstore':
				$label = 'Application has been successfully sent to AppStore.';
				break;
			case 'appstore':
				$label = 'Application is running properly.';
				break;
			case 'rejected':
				$label = 'Application has been rejected by AppStore. Don\'t panic! We are working on that.';
				break;
			default:
				break;
		}
		
		return $label;
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
		return $this->ticket() ? true : false;
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
	
	public function icon($htmlOptions = array())
	{
		return YsaHtml::image($this->iconUrl(), 'iPad Icon', $htmlOptions);
	}
	
	public function iconUrl()
	{
		$option = $this->option('icon');
		
		if ($option) {
			$url = $option['url'];
		} else {
			$url = ImageHelper::thumb(
				Yii::app()->params['application']['icon']['width'], 
				Yii::app()->params['application']['icon']['height'],
				ImageHelper::defaultImagePath()
			);
		}
		
		return $url;
	}

	public function isPaid()
	{
		return $this->paid;
	}

	public function createTransaction()
	{
		$transaction = new PaymentTransaction();
		$transaction->state = $transaction::STATE_CREATED;
		$transaction->name = 'Application Initial Payment';
		$transaction->description = 'Initial payment for the creation of YSApplication.';
		$transaction->summ = (float)Yii::app()->settings->get('application_summ');
		$transaction->created = date('Y.m.d H:i:s');
		$transaction->save();
		$transactionApplication = new PaymentTransactionApplication();
		$transactionApplication->application_id = $this->id;
		$transactionApplication->transaction_id = $transaction->id;
		$transactionApplication->save();
		return $transaction;
	}
}