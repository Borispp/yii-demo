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
 * @property string $order_link
 * @property string $video
 *
 * @property User $user
 * @property array $links
 * @property array $customLinks
 * @property array $bookmarkLinks
 * @property array $persons
 */
class Studio extends YsaActiveRecord
{
	protected $_uploadPath;
	
	protected $_uploadUrl;
	
	const VIDEO_YOUTUBE = 'youtube';
	
	const VIDEO_VIMEO = 'vimeo';
	
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
		return array(
			array('user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('name, facebook_feed, twitter_feed, blog_feed', 'length', 'max'=>100),
			array('facebook_feed, twitter_feed, blog_feed, order_link', 'url'),
			array('name, facebook_feed, twitter_feed, blog_feed, order_link, created, updated, specials, video', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user'			=> array(self::BELONGS_TO, 'User', 'user_id'),
			'links'			=> array(self::HAS_MANY, 'StudioLink', 'studio_id', 'order' => 'rank ASC'),
			'customLinks'	=> array(self::HAS_MANY, 'StudioLink', 'studio_id', 'order' => 'rank ASC', 'condition' => 'type=:type', 'params' => array('type' => StudioLink::TYPE_CUSTOM)),
			'bookmarkLinks'	=> array(self::HAS_MANY, 'StudioLink', 'studio_id', 'order' => 'rank ASC', 'condition' => 'type=:type', 'params' => array('type' => StudioLink::TYPE_BOOKMARK)),
			'persons'		=> array(self::HAS_MANY, 'StudioPerson', 'studio_id', 'order' => 'rank ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'			=> 'ID',
			'user_id'		=> 'User',
			'name'			=> 'Name',
			'created'		=> 'Created',
			'updated'		=> 'Updated',
			'specials'		=> 'Specials',
			'facebook_feed'	=> 'Facebook',
			'twitter_feed'	=> 'Twitter',
			'blog_feed'		=> 'Blog RSS',
			'order_link'	=> 'Order Link',
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
	
	public function specialsExtention()
	{
		$path = $this->_uploadPath . DIRECTORY_SEPARATOR . $this->specials;
		if (is_file($path)) {
			return YsaHelpers::mimeToExtention(mime_content_type($path));
		} else {
			return false;
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
	
	public function addVideo($url, $type, $code, $options = array())
	{
		if (!isset($url) || !isset($type) || !isset($code)) {
			return false;
		}
		
		$data = array(
			'url'		=> $url,
			'type'		=> $type,
			'code'		=> $code,
			'options'	=> $options,
		);
		
		$this->video = serialize($data);
		$this->save();
		
		return $this;
	}
	
	public function video($size = 'standart')
	{
		if (!$this->video) {
			return false;
		}
		
		$video = unserialize($this->video);
		
		$size = Yii::app()->params['studio']['video'][$size];
		
		if (!$size) {
			$size = Yii::app()->params['studio']['video']['standart'];
		}
		
		$html = '';
		switch ($video['type']) {
			case Studio::VIDEO_VIMEO:
				$html = '<iframe src="http://player.vimeo.com/video/' . $video['code'] . '?title=0&amp;byline=0&amp;portrait=0" width="' . $size['width'] . '" height="' . $size['height'] . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
				break;
			case Studio::VIDEO_YOUTUBE:
				$html = '<iframe width="' . $size['width'] . '" height="' . $size['height'] . '" src="http://www.youtube.com/embed/' . $video['code'] . '" frameborder="0" allowfullscreen></iframe>';
				break;
			default:
				break;
		}
		
		return $html;
	}
	
	public function deleteVideo()
	{
		$this->video = '';
		$this->save();
		
		return $this;
	}
}