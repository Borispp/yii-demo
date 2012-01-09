<?php

/**
 * This is the model class for table "user_option".
 *
 * The followings are the available columns in table 'user_option':
 * @property string $id
 * @property integer $user_id
 * @property string $name
 * @property string $blog_feed
 * @property string $twitter_feed
 * @property string $facebook_feed
 * @property string $created
 * @property string $updated
 * @property string $specials
 */
class Studio extends YsaActiveRecord
{
	protected $_uploadPath;
	
	protected $_uploadUrl;
	
    public function init() {
        parent::init();
        
        $this->_uploadPath = rtrim(Yii::getPathOfAlias('webroot.images.studio'), '/');
        $this->_uploadUrl = Yii::app()->getBaseUrl(true) . '/images/studio';
		
		if (!is_dir($this->_uploadPath)) {
			mkdir($this->_uploadPath, 0777);
		}
    }
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'studio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('name, facebook_feed, twitter_feed, blog_feed', 'length', 'max'=>100),
			array('facebook_feed, twitter_feed, blog_feed', 'url'),
			array('name, facebook_feed, twitter_feed, blog_feed, created, updated, specials', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user'		=> array(self::BELONGS_TO, 'User', 'user_id'),
			'links'		=> array(self::HAS_MANY, 'StudioLink', 'studio_id'),
			'persons'	=> array(self::HAS_MANY, 'StudioPerson', 'studio_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'name' => 'Name',
			'created' => 'Created',
			'updated' => 'Updated',
			'specials' => 'Specials',
			'facebook_feed' => 'Facebook',
			'twitter_feed' => 'Twitter',
			'blog_feed' => 'Blog RSS',
			
		);
	}
	
	public function isOwner()
	{
		return $this->user_id == Yii::app()->user->id;
	}
	
	public function uploadPath()
	{
		return $this->_uploadPath;
	}
	
	public function specials()
	{
		$path = $this->_uploadPath . DIRECTORY_SEPARATOR . $this->specials;
		
		$url = $this->specialsUrl();
		
		$ext = YsaHelpers::mimeToExtention(mime_content_type($path));
		
		switch($ext) {
			case 'pdf':
				return 'View PDF';
				break;
			default:
				return YsaHtml::image($url);
				break;
		}
	}
	
	public function specialsUrl()
	{
		return $this->_uploadUrl . '/' . $this->specials;
	}
	
	public function uploadUrl()
	{
		return $this->_uploadUrl;
	}
	
	public function saveSpecials(CUploadedFile $instance)
	{		
		$ext = YsaHelpers::mimeToExtention($instance->getType());
		
		if (!$ext) {
			return false;
		}
		
        $newName = YsaHelpers::encrypt('specials' . $this->id) . '.' . $ext;

        $savePath = $this->uploadPath() . DIRECTORY_SEPARATOR . $newName;
		
		$this->specials = $newName;
		
		$instance->saveAs($savePath);
		$this->save();
		
		return $this;
	}
	
	public function deleteSpecials()
	{
		$path = $this->_uploadPath . DIRECTORY_SEPARATOR . $this->specials;
		
		if (is_file($path)) {
			unlink($path);
		}
		
		$this->specials = null;
		
		$this->save();
		
		return $this;
	}
}