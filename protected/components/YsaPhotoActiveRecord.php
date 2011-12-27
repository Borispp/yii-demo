<?php
class YsaPhotoActiveRecord extends YsaActiveRecord
{
	protected $_exif;
	
	protected $_album;
	
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
			array('meta_type, alt, rank, created, updated, exif_data, size, imported_data', 'safe'),
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
			$this->exif_data = serialize($data);
		}
		
		$this->generateBaseName();
		
		$savePath = $this->album->albumPath() . DIRECTORY_SEPARATOR . $this->basename . '.' . $this->extention;
		
		$image->quality(100);
		$image->resize(
			Yii::app()->params['member_area']['photo']['full']['width'], 
			Yii::app()->params['member_area']['photo']['full']['height']
		);
		
		$image->save($savePath);
		
		$this->size = filesize($savePath);
		
		if ($save) {
			$this->save();
		}
		
		return true;
	}
	
	public function defaultPicUrl($width = 300, $height = 200)
	{	
		return ImageHelper::thumb($width, $height, rtrim(Yii::getPathOfAlias('webroot.resources.images'), '/') . DIRECTORY_SEPARATOR . 'no-image.png');
	}

	public function getChecksum()
	{
		return md5($this->basename);
	}
}