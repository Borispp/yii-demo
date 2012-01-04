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
			'album'		=> array(self::BELONGS_TO, 'EventAlbum', 'album_id'),
			'sizes'		=> array(self::MANY_MANY, 'PhotoSize', 'event_photo_size(photo_id, size_id)'),
			'comments'	=> array(self::HAS_MANY, 'EventPhotoComment', 'photo_id', 'order' => 'created DESC'),
			'rates'		=> array(self::HAS_MANY, 'EventPhotoRate', 'photo_id'),
			'rating'	=> array(self::STAT, 'EventPhotoRate', 'photo_id', 'select' =>'ROUND(AVG(rate), 2)', 'group' => 'photo_id'),
		);
	}

	/**
	 *
	 * @return Album
	 */
//	public function album()
//	{
//		if (null === $this->_album) {
//			$this->_album = EventAlbum::model()->findByPk($this->album_id);
//		}
//
//		return $this->_album;
//	}
	
//	public function comments()
//	{
//		if (null === $this->_comments) {
//			$this->_comments = EventPhotoComment::model()->findAll(array(
//				'condition' => 'photo_id=:photo_id',
//				'params'	=> array(
//					'photo_id' => $this->id,
//				),
//				'order' => 'created DESC',
//			));	
//		}
//		
//		return $this->_comments;
//	}
	
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
			$this->exif_data = serialize($data['exif']);
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
	
	public function shareUrl()
	{
		return Yii::app()->createAbsoluteUrl('photo/v/' . $this->basename);
	}
	
	public function shareLink($title = 'Share URL', $htmlOptions = array())
	{
		return YsaHtml::link($title, $this->shareUrl(), $htmlOptions);
	}
}