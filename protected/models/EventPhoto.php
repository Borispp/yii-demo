<?php

/**
 * This is the model class for table "event_album".
 *
 * The followings are the available columns in table 'event_album':
 * @property integer $id
 * @property integer $album_id
 * @property string $basename
 * @property string $name
 * @property string $extention
 * @property string $meta_type
 * @property string $alt
 * @property integer $state
 * @property integer $rank
 * @property string $created
 * @property string $updated
 * @property string $exif_data
 */
class EventPhoto extends YsaActiveRecord
{
    protected $_album;
	
	protected $_exif;
	
	protected $_comments;
    
    /**
     * Returns the static model of the specified AR class.
     * @return EventPhoto the static model class
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
		return 'event_photo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('album_id, rank, state', 'numerical', 'integerOnly'=>true),
			array('album_id, basename, extention', 'required'),
			array('basename, name', 'length', 'max'=>100),
			array('extention', 'length', 'max'=>5),
			array('meta_type', 'length', 'max'=>20),
			array('alt', 'length', 'max'=>255),
			array('meta_type, alt, rank, created, updated, exif_data', 'safe'),
		);
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'album'  => array(self::BELONGS_TO, 'EventAlbum', 'album_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
		return array();
    }

	
	/**
	 *
	 * @return Album
	 */
    public function album()
    {
        if (null === $this->_album) {
            $this->_album = EventAlbum::model()->findByPk($this->album_id);
        }

        return $this->_album;
    }
	
	public function previewUrl($width = 300, $height = 200)
	{
		if (is_file($this->path())) {
			return ImageHelper::thumb($width, $height, $this->path());
		} else {
			return '';
		}
	}
	
	public function preview($width = 300, $height = 200, $htmlOptions = array())
	{
		return YsaHtml::image($this->previewUrl($width, $height), $this->alt, $htmlOptions);
	}
	
	public function full($htmlOptions = array())
	{
		return YsaHtml::image($this->fullUrl(), $this->alt, $htmlOptions);
	}
	
	public function fullUrl()
	{
		return $this->url();
	}
	
	public function upload(CUploadedFile $instance, $save = true)
	{
		if (!$this->album_id) {
			throw new CException('Please fill photo Album ID');
		}
		
		$image = new Image($instance->getTempName());
		
		$this->name = $instance->getName();
		$this->meta_type = $image->mime;
		$this->extention = YsaHelpers::mimeToExtention($this->meta_type);
		$this->alt = '';
		$this->state = self::STATE_ACTIVE;
		
		// read exif data from jpegs
		if ($this->extention === 'jpg') {
			$data = exif_read_data($instance->getTempName());
			
			$this->exif_data = serialize($data);
			
//			if (isset($data['Orientation'])) {
//				
//			}
		}
		
		$this->generateBaseName();
		
		$savePath = $this->album()->albumPath() . DIRECTORY_SEPARATOR . $this->basename . '.' . $this->extention;
		
		$image->quality(100);
		$image->resize(
			Yii::app()->params['member_area']['photo']['full']['width'], 
			Yii::app()->params['member_area']['photo']['full']['height']
		);
		
		$image->save($savePath);
		
		if ($save) {
			$this->save();
		}
		
		return true;
	}
	
	public function generateBaseName()
	{
		$this->basename = YsaHelpers::encrypt(microtime() . $this->name . $this->album_id);
	}
	
	public function url()
	{
		return $this->album()->albumUrl() . '/' .  $this->basename . '.' . $this->extention;
	}
	
	public function path()
	{
		return $this->album()->albumPath() . DIRECTORY_SEPARATOR .  $this->basename . '.' . $this->extention;
	}
	
	public function setNextRank()
	{	
		$maxRank = (int) Yii::app()->db->createCommand()
							->select('max(rank) as max')
							->from($this->tableName())
							->where('album_id=:album_id', array(':album_id' => $this->album_id))
							->queryScalar();
		
		$this->rank = $maxRank + 1;
	}
	
	/**
	 * Delete image before AR
	 */
	public function beforeDelete() {
		parent::beforeDelete();
		$file = $this->path();

		if (is_file($file)) {
			unlink($file);
		}
		return true;
	}
	
	/**
	 * Set next rank for album photo
	 * @return bool
	 */
    public function beforeSave() 
	{
        if($this->isNewRecord) {
            $this->setNextRank();
        }
        return parent::beforeValidate();
    }
	
	/**
	 * Get EXIF Data information
	 * @return array
	 */
	public function exif()
	{
		if (null === $this->_exif) {
			if (YsaHelpers::isSerialized($this->exif_data)) {
				$this->_exif = unserialize($this->exif_data);
			} else {
				$this->_exif = $this->exif_data;
			}			
		}
		
		return $this->_exif;
	}
	
	public function comments()
	{
		if (null === $this->_comments) {
			$this->_comments = EventPhotoComment::model()->findAll(array(
				'condition' => 'photo_id=:photo_id',
				'params'	=> array(
					'photo_id' => $this->id,
				),
				'order' => 'created DESC',
			));	
		}
		
		return $this->_comments;
	}
}