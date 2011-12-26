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
}