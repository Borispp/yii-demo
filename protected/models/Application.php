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
	protected $_ticket;
	
	public $default_style;
	
	protected $_steps = array(
		'logo' => array(
			'position' => 1,
			'title' => 'Icons/Logos',
			'short_title' => 'Icons/Logos',
			'title_annotation' => 'Upload your icons &amp; logos',
			'header' => 'Upload your icons &amp; logos',
		),
		'colors' => array(
			'position' => 2,
			'title' => 'Design Your Backgrounds',
			'short_title' => 'Backgrounds',
			'title_annotation' => 'Colors &amp; images',
			'header' => 'Design Your Backgrounds',
		),
		'fonts' => array(
			'position' => 3,
			'title' => 'Choose Your Fonts and Colors',
			'short_title' => 'Fonts',
			'title_annotation' => 'Your Typography',
			'header' => 'Choose Your Fonts and Colors',
		),
		'copyrights' => array(
			'position' => 4,
			'title' => 'Insert Your Copyright',
			'short_title' => 'Copyrights',
			'title_annotation' => 'Copyright your app',
			'header' => 'Insert Your Copyright',
		),
		'submit' => array(
			'position' => 5,
			'title' => 'Submit Your Application',
			'short_title' => 'Submit',
			'title_annotation' => 'It\'s all done!',
			'header' => 'Purchase and Submit Your Application',
		),	
	);
	
	protected $_requiredOptions = array(
		'icon',
		'logo',
		'itunes_logo',
		'splash_bg_image',
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
			'default_style' => 'Main Color Scheme',
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
//		$this->notifyByEmail('application_ready');
		if ($log) {
			$this->log('ready');
		}
		return $this;
	}

	public function notifyByEmail($template)
	{
		$this->log('Notified user by email with '.$template.' template');
		Email::model()->send(
			array($this->user->email, $this->user->name()),
			$template,
			array(
				'name'  => $this->user->name(),
				'email' => $this->user->email,
			)
		);
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
		return $this->filled . $this->paid . $this->submitted . $this->locked . $this->approved . $this->ready;
	}
	
	public function status()
	{
		$statusDictionary = array(
			'000000'  => 'newly-created',
			'100000'  => 'filled',
			'110000'  => 'paid',
			'111000'  => 'submitted',
			'111100'  => 'locked',
			'111110'  => 'approved',
			'111111'  => 'appstore',
			'111112'  => 'running',
			'11111-1' => 'rejected',
			'1111-10' => 'unapproved',
			'1110-10' => 'unapproved',
		);
		
		if (!isset($statusDictionary[$this->numStatus()]))
		{
			Yii::log('Unknown application status ['.$this->numStatus().']', CLogger::LEVEL_ERROR);
			return 'unknown';
		}
		
		return $statusDictionary[$this->numStatus()];
	}
	
	public function statusLabel()
	{
		$labelDictionary = array(
			'newly-created' => 'Your application is missing some key elements, please provide missing content before submitting.',
			'paid'          => Yii::t('application', 'paid_block_text'),
			'filled'        => 'All custom elements of your application have been provided.',
			'submitted'     => 'Application has been successfully submitted.',
			'locked'        => 'Application has been locked to prevent any changes.',
			'approved'      => 'Application has been successfully approved by moderators.',
			'appstore'      => 'Application has been successfully sent to AppStore.',
			'running'      => 'Application is running properly.',
			'rejected'      => 'YSA support will be in touch with you soon.',
		);
		
		if (!array_key_exists($this->status(), $labelDictionary))
		{
			Yii::log('Unknown application status ['.$this->status().']', CLogger::LEVEL_ERROR);
			return 'unknown';
		}
		
		return $labelDictionary[$this->status()];
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
		foreach ($this->getRequiredOptions() as $opt) {
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

	public function editOption($name, $value, $type = null, $resize = false)
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
				
				if ($resize) {
					$image->resize($width, $height);
				} else {
					$image->auto_crop($width, $height);
				}
				
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

	/**
	 * Check if current application is paid by member
	 * @return int
	 */
	public function isPaid()
	{
		return $this->paid;
	}

	public function createTransaction($name = NULL, $summ = NULL)
	{
		$transaction = new PaymentTransaction();
		$transaction->state = PaymentTransaction::STATE_CREATED;
		$transaction->name = $name ? $name : 'Application Initial Payment';
		$transaction->description = 'Initial payment for the creation of YSApplication.';
		$transaction->summ = (float)($summ ? $summ : Yii::app()->settings->get('application_summ'));
		$transaction->created = date('Y.m.d H:i:s');
		$transaction->save();
		$transactionApplication = new PaymentTransactionApplication();
		$transactionApplication->application_id = $this->id;
		$transactionApplication->transaction_id = $transaction->id;
		$transactionApplication->save();
		return $transaction;
	}
	
	public function imageUrl($name, $width = 72, $height = 72)
	{
		$image = $this->option($name);
		
		if ($image && isset($image['url']) && is_file($image['path'])) {
			return ImageHelper::thumb($width, $height, $image['path']);
		} else {
			return EventPhoto::model()->defaultPicUrl($width, $height);
		}
	}
	
	public function image($name, $width = 72, $height = 72, $alt = '', $htmlOptions = array())
	{
		return YsaHtml::image($this->imageUrl($name, $width, $height), $alt, $htmlOptions);
	}
}