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
 * @property string $size
 */
class EventPhoto extends YsaPhotoActiveRecord
{
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
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'album'  => array(self::BELONGS_TO, 'EventAlbum', 'album_id'),
			'sizes'	 => array(self::MANY_MANY, 'PhotoSize', 'event_photo_size(photo_id, size_id)'),
        );
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
	
	public function isOwner()
	{
		return $this->album()->event()->isOwner();
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
		return $this->album()->can_share ? $this->can_share : false;
	}
	
	public function canOrder()
	{
		return $this->album()->can_order ? $this->can_order : false;
	}
	
	public function import($data, $from = 'smugmug', $save = true)
	{
		if (!$this->album_id) {
			throw new CException('Please fill photo Album ID');
		}
		
		
		if ($from == 'smugmug') {
			$this->_importSmugmug($data);
		}
		
		if ($save) {
			$this->save();
		}
		
		return $this;
	}
	
	
//array
//(
//    'id' => 1028032342
//    'Key' => 'A5f9T'
//    'Album' => array
//    (
//        'id' => 13982702
//        'Key' => '4VMffZ'
//        'URL' => 'http://gallery.matthewcotter.com/Portfolio/Favorites/13982702_4VMffZ#1028032342_A5f9T'
//    )
//    'Caption' => ''
//    'CustomURL' => 'http://gallery.matthewcotter.com/photos/1028032342_A5f9T-1024x768.jpg'
//    'Date' => '2010-09-29 20:33:36'
//    'FileName' => 'ETP-7863.jpg'
//    'Format' => 'JPG'
//    'Height' => 3744
//    'Hidden' => false
//    'Keywords' => 'Athens, Cruise, Europe, Greece, Mediterranean, Vacation, activities, activity,'
//    'LargeURL' => 'http://gallery.matthewcotter.com/photos/1028032342_A5f9T-L.jpg'
//    'LastUpdated' => '2010-09-29 20:34:13'
//    'LightboxURL' => 'http://gallery.matthewcotter.com/Portfolio/Favorites/13982702_4VMffZ#1028032342_A5f9T-A-LB'
//    'MD5Sum' => '191491b45df7a1907225cc75e6404403'
//    'MediumURL' => 'http://gallery.matthewcotter.com/photos/1028032342_A5f9T-M.jpg'
//    'OriginalURL' => 'http://gallery.matthewcotter.com/photos/1028032342_A5f9T-O.jpg'
//    'Position' => 1
//    'Serial' => 0
//    'Size' => 6574925
//    'SmallURL' => 'http://gallery.matthewcotter.com/photos/1028032342_A5f9T-S.jpg'
//    'ThumbURL' => 'http://gallery.matthewcotter.com/photos/1028032342_A5f9T-Th.jpg'
//    'TinyURL' => 'http://gallery.matthewcotter.com/photos/1028032342_A5f9T-Ti.jpg'
//    'Type' => 'Album'
//    'URL' => 'http://gallery.matthewcotter.com/Portfolio/Favorites/13982702_4VMffZ#1028032342_A5f9T'
//    'Watermark' => false
//    'Width' => 5616
//    'X2LargeURL' => 'http://gallery.matthewcotter.com/photos/1028032342_A5f9T-X2.jpg'
//    'X3LargeURL' => 'http://gallery.matthewcotter.com/photos/1028032342_A5f9T-X3.jpg'
//    'XLargeURL' => 'http://gallery.matthewcotter.com/photos/1028032342_A5f9T-XL.jpg'
//    'exif' => array
//    (
//        'id' => 1028032342
//        'Key' => 'A5f9T'
//        'Aperture' => '16/1'
//        'DateTime' => '2010-09-29 20:27:47'
//        'DateTimeDigitized' => '2010-08-24 11:26:26'
//        'DateTimeOriginal' => '2010-08-24 11:26:26'
//        'ExposureBiasValue' => '0/1'
//        'ExposureMode' => 1
//        'ExposureProgram' => 1
//        'ExposureTime' => '1/125'
//        'Flash' => 16
//        'FocalLength' => '24/1'
//        'ISO' => 100
//        'Make' => 'Canon'
//        'Model' => 'Canon EOS 5D Mark II'
//        'Metering' => 6
//        'SubjectDistance' => '504/100'
//        'WhiteBalance' => 0
//    )
//)
	
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
		
		if (isset($data['exif'])) {
			$this->exif_data = serialize($data['exif']);
		}
		
		$this->generateBaseName();
		
		$savePath = $this->album()->albumPath() . DIRECTORY_SEPARATOR . $this->basename . '.' . $this->extention;
		
		$image->quality(100);
		$image->resize(
			Yii::app()->params['member_area']['photo']['full']['width'], 
			Yii::app()->params['member_area']['photo']['full']['height']
		);
		
		$image->save($savePath);
		
		$this->size = filesize($savePath);
	}
	
	public function shareUrl()
	{
		return Yii::app()->createAbsoluteUrl('photo/v/' . $this->basename);
	}
}