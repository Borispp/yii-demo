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
 * @property integer $can_share
 * @property integer $can_order
 * @property integer $size
 * @property integer $original_size
 * @property integer $imported
 * @property string $imported_data
 */
class EventPhoto extends YsaActiveRecord
{
	protected $_comments;
	
	protected $_exif;
	
	protected $_album;
	
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
			array('album_id, rank, state, imported', 'numerical', 'integerOnly'=>true),
			array('album_id, basename, extention', 'required'),
			array('basename, name', 'length', 'max'=>100),
			array('extention', 'length', 'max'=>5),
			array('meta_type', 'length', 'max'=>20),
			array('alt', 'length', 'max'=>255),
			array('meta_type, alt, rank, created, updated, exif_data, size, original_size, imported_data', 'safe'),
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
			'album'		=> array(self::BELONGS_TO, 'EventAlbum', 'album_id'),
			'sizes'		=> array(self::MANY_MANY, 'PhotoSize', 'event_photo_size(photo_id, size_id)'),
			'comments'	=> array(self::HAS_MANY, 'EventPhotoComment', 'photo_id', 'order' => 'created DESC'),
			'rates'		=> array(self::HAS_MANY, 'EventPhotoRate', 'photo_id'),
			'rating'	=> array(self::STAT, 'EventPhotoRate', 'photo_id', 'select' =>'ROUND(AVG(rate), 2)', 'group' => 'photo_id'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'album_id' => 'Album',
			'name' => 'Name',
			'basename' => 'Basename',
			'extention' => 'Extention',
			'meta_type' => 'Meta Type',
			'exif_data' => 'Exif Data',
			'alt' => 'Alt',
			'state' => 'State',
			'rank' => 'Rank',
			'created' => 'Created',
			'updated' => 'Updated',
			'can_share' => 'Can be shared',
			'can_order' => 'Can be ordered',
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('album_id',$this->album_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('rank',$this->rank);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
	 * Delete image before AR
	 */
	public function beforeDelete() 
	{
		parent::beforeDelete();
		
		$file = $this->path();
		if (is_file($file)) {
			unlink($file);
		}
		
		$original_file = $this->originPath();
		if (is_file($original_file)) {
			unlink($original_file);
		}
				
		if ($this->album->cover_id == $this->id) {
			$this->album->changeCover($this->id);
		}
		
		return true;
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
	
	/**
	 * Apply next rank to new photo 
	 */
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
	 * Generate BaseName for new Image
	 */
	public function generateBaseName()
	{
		$this->basename = YsaHelpers::encrypt(microtime() . $this->name . $this->album_id);
	}
	
	/**
	 * Image URL
	 * @return string
	 */
	public function url()
	{
		return $this->album->albumUrl() . '/' .  $this->basename . '.' . $this->extention;
	}
	
	/**
	 * Image path
	 * @return string
	 */
	public function path()
	{
		return $this->album->albumPath() . DIRECTORY_SEPARATOR .  $this->basename . '.' . $this->extention;
	}
	
	/**
	 * Original image path
	 * @return string
	 */
	public function originPath()
	{
		return $this->album->originPath() . DIRECTORY_SEPARATOR .  $this->basename . '.' . $this->extention;
	}
	
	/**
	 * Image Full URL
	 * @return string
	 */
	public function fullUrl()
	{
		return $this->url();
	}
	
	/**
	 * Get preview URL
	 * 
	 * @param integer $width
	 * @param integer $height
	 * @return string 
	 */
	public function previewUrl($width = 300, $height = 200)
	{
		if (is_file($this->path())) {
			return ImageHelper::thumb($width, $height, $this->path());
		} else {
			return $this->defaultPicUrl($width, $height);
		}
	}
	
	/**
	 * Show preview image
	 * 
	 * @param integer $width
	 * @param integer $height
	 * @param array $htmlOptions
	 * @return string
	 */
	public function preview($width = 300, $height = 200, $htmlOptions = array())
	{
		return YsaHtml::image($this->previewUrl($width, $height), $this->alt, $htmlOptions);
	}
	
	/**
	 * Display Full Image
	 * @param array $htmlOptions
	 * @return string
	 */
	public function full($htmlOptions = array())
	{
		return YsaHtml::image($this->fullUrl(), $this->alt, $htmlOptions);
	}
	
	/**
	 * Upload an image, resize and save.
	 * 
	 * @param CUploadedFile $instance
	 * @param bool $save
	 * @return bool
	 */
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
			$data = @exif_read_data($instance->getTempName());
			
			$exif = array();
			
			if (isset($data['ApertureValue'])) {
				$exif['aperture'] = $data['ApertureValue'];
			}
			if (isset($data['DateTime'])) {
				$exif['date_time'] = $data['DateTime'];
			}
			if (isset($data['DateTimeDigitized'])) {
				$exif['date_time_digitized'] = $data['DateTimeDigitized'];
			}
			if (isset($data['DateTimeOriginal'])) {
				$exif['date_time_original'] = $data['DateTimeOriginal'];
			}
			if (isset($data['ExposureBiasValue'])) {
				$exif['exposure_bias_value'] = $data['ExposureBiasValue'];
			}
			if (isset($data['ExposureMode'])) {
				$exif['exposure_mode'] = $data['ExposureMode'];
			}
			if (isset($data['ExposureProgram'])) {
				$exif['exposure_program'] = $data['ExposureProgram'];
			}
			if (isset($data['ExposureTime'])) {
				$exif['exposure_time'] = $data['ExposureTime'];
			}
			if (isset($data['Flash'])) {
				$exif['flash'] = $data['Flash'];
			}
			if (isset($data['FocalLength'])) {
				$exif['focal_length'] = $data['FocalLength'];
			}
			if (isset($data['ISOSpeedRatings'])) {
				$exif['iso'] = $data['ISOSpeedRatings'];
			}
			if (isset($data['Make'])) {
				$exif['make'] = $data['Make'];
			}
			if (isset($data['Model'])) {
				$exif['model'] = $data['Model'];
			}
			if (isset($data['MeteringMode'])) {
				$exif['metering'] = $data['MeteringMode'];
			}
			if (isset($data['WhiteBalance'])) {
				$exif['white_balance'] = $data['WhiteBalance'];
			}
			if (isset($data['Orientation'])) {
				$exif['orientation'] = $data['Orientation'];
			}			
			if (isset($data['FNumber'])) {
				$exif['subject_distance'] = $data['FNumber'];
			}

			$this->exif_data = serialize($exif);
		}
		
		$this->generateBaseName();
		
		$image->quality(100);
				
		$original_image = clone $image;
				
		$image->resize(
			Yii::app()->params['member_area']['photo']['full']['width'], 
			Yii::app()->params['member_area']['photo']['full']['height']
		);
		
		$savePath = $this->path();
		$image->save($savePath);
		$this->size = filesize($savePath);
		
		$original_save_path = $this->originPath();
		$original_image->save( $original_save_path );
		$this->original_size = filesize($original_save_path);
				
		if ($save) {
			$this->save();
		}
		
		return true;
	}
	
	/**
	 * Get default picture if photo doesn't exist
	 * 
	 * @param int $width
	 * @param int $height
	 * @return string
	 */
	public function defaultPicUrl($width = 300, $height = 200)
	{	
		return ImageHelper::thumb($width, $height, rtrim(Yii::getPathOfAlias('webroot.resources.images'), '/') . DIRECTORY_SEPARATOR . 'no-image.png');
	}

	/**
	 * Get photo checksum
	 * 
	 * @return string
	 */
	public function getChecksum()
	{
		return md5($this->basename);
	}
	
	/**
	 * Check for album cover
	 * 
	 * @return bool
	 */
	public function isCover()
	{
		return $this->album->cover_id == $this->id;
	}
	
	/**
	 * Check if logged in member is current photo's owner
	 * @return type 
	 */
	public function isOwner()
	{
		return $this->album->event->isOwner();
	}
	
	public function setSizes($sizes)
	{
		EventPhotoSize::model()->deleteAll('photo_id=:photo_id', array(
			':photo_id' => $this->id,
		));
		
		foreach ($sizes as $size) {
			$sizeEntry = PhotoSize::model()->findByAttributes(array(
				'id'	=> (int) $size,
				'state' => PhotoSize::STATE_ACTIVE,
			));
			
			if ($sizeEntry) {
				
				$s = new EventPhotoSize();
				$s->setAttributes(array(
					'size_id'  => $sizeEntry->id,
					'photo_id' => $this->id,					
				));
				$s->save();
				unset($s);
			}
		}
		
		return $this;
	}
	
	public function canShare()
	{
		return $this->album->can_share ? $this->can_share : false;
	}
	
	public function canOrder()
	{
		return $this->album->can_order ? $this->can_order : false;
	}
	
	public function rating()
	{
		return sprintf('%.2f', $this->rating);
	}
	
	public function import($data, $from = 'smugmug', $save = true)
	{
		if (!$this->album_id) {
			throw new CException('Please fill photo Album ID');
		}
		
		$this->imported = true;
		
		if ($from == 'smugmug') {
			$this->_importSmugmug($data);
		}
		
		if ($save) {
			$this->save();
		}
		
		return $this;
	}
	
	protected function _importSmugmug($data)
	{
		$target = tempnam(sys_get_temp_dir(), 'img');
		
		$fh = fopen($target,'w');
		$check = fwrite($fh,file_get_contents($data['CustomURL']));
		fclose($fh);
		
		if (!$check) {
			throw new Exception('SmugMug image file can\'t be read. Please try again later.');
		}
		
		$image = new Image($target);
		
		$this->name = $data['FileName'];
		$this->meta_type = $image->mime;
		$this->extention = YsaHelpers::mimeToExtention($this->meta_type);
		$this->alt = $data['Caption'];
		$this->state = self::STATE_ACTIVE;
		
		$data['from'] = 'smugmug';
		$this->imported_data = serialize($data);
		
		if (isset($data['exif'])) {
			$this->exif_data = $this->_formatSmugmugExif($data['exif']);
		}
		
		$this->generateBaseName();

		$image->quality(100);
		
		$original_image = clone $image;
		
		$image->resize(
			Yii::app()->params['member_area']['photo']['full']['width'], 
			Yii::app()->params['member_area']['photo']['full']['height']
		);
                
		$savePath = $this->path();
		$image->save($savePath);
		$this->size = filesize($savePath);
		
		$original_save_path = $this->originPath();
		$original_image->save( $original_save_path );
		$this->original_size = filesize($original_save_path);
	}
	
	protected function _formatSmugmugExif($data)
	{
		$exif = array();

		if (isset($data['Aperture'])) {
			$exif['aperture'] = $data['Aperture'];
		}
		if (isset($data['DateTime'])) {
			$exif['date_time'] = $data['DateTime'];
		}
		if (isset($data['DateTimeDigitized'])) {
			$exif['date_time_digitized'] = $data['DateTimeDigitized'];
		}
		if (isset($data['DateTimeOriginal'])) {
			$exif['date_time_original'] = $data['DateTimeOriginal'];
		}
		if (isset($data['ExposureBiasValue'])) {
			$exif['exposure_bias_value'] = $data['ExposureBiasValue'];
		}
		if (isset($data['ExposureMode'])) {
			$exif['exposure_mode'] = $data['ExposureMode'];
		}
		if (isset($data['ExposureProgram'])) {
			$exif['exposure_program'] = $data['ExposureProgram'];
		}
		if (isset($data['ExposureTime'])) {
			$exif['exposure_time'] = $data['ExposureTime'];
		}
		if (isset($data['Flash'])) {
			$exif['flash'] = $data['Flash'];
		}
		if (isset($data['FocalLength'])) {
			$exif['focal_length'] = $data['FocalLength'];
		}
		if (isset($data['ISO'])) {
			$exif['iso'] = $data['ISO'];
		}
		if (isset($data['Make'])) {
			$exif['make'] = $data['Make'];
		}
		if (isset($data['Model'])) {
			$exif['model'] = $data['Model'];
		}
		if (isset($data['Metering'])) {
			$exif['metering'] = $data['Metering'];
		}
		if (isset($data['WhiteBalance'])) {
			$exif['white_balance'] = $data['WhiteBalance'];
		}
		if (isset($data['Orientation'])) {
			$exif['orientation'] = $data['Orientation'];
		}			
		if (isset($data['SubjectDistance'])) {
			$exif['subject_distance'] = $data['SubjectDistance'];
		}
		
		return $exif;
	}
	
	public function shareUrl()
	{
		return Yii::app()->createAbsoluteUrl('photo/v/' . $this->basename);
	}
	
	public function shareLink($title = 'Share URL', $htmlOptions = array())
	{
		return YsaHtml::link($title, $this->shareUrl(), $htmlOptions);
	}
}