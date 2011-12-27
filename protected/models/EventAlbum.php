<?php

/**
 * This is the model class for table "event_album".
 *
 * The followings are the available columns in table 'event_album':
 * @property string $id
 * @property integer $event_id
 * @property string $name
 * @property string $description
 * @property string $shooting_date
 * @property string $place
 * @property integer $rank
 * @property integer $state
 * @property string $created
 * @property string $updated
 * @property integer $can_order
 * @property integer $can_share
 * 
 * @property EventPhoto $photo
 * @property Event $event
 */
class EventAlbum extends YsaAlbumActiveRecord
{
    const PROOFING_NAME = 'Proofing Album';
	
    public function init() {
        parent::init();
        
        $this->_uploadPath = rtrim(Yii::getPathOfAlias('webroot.images.albums'), '/');
        $this->_uploadUrl = Yii::app()->getBaseUrl(true) . '/images/albums';
		$this->_createDir();
    }
	
    /**
     * Returns the static model of the specified AR class.
     * @return EventAlbum the static model class
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
		return 'event_album';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
		return array(
			array('event_id, rank, state, can_share, can_order', 'numerical', 'integerOnly'=>true),
			array('name, place', 'length', 'max'=>255),
			array('event_id, state, name', 'required'),
			array('description, shooting_date, created, updated, can_share, can_order', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, name, state, created', 'safe', 'on'=>'search'),
		);
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'event'		=> array(self::BELONGS_TO, 'Event', 'event_id'),
            'photos'	=> array(self::HAS_MANY, 'EventPhoto', 'album_id', 'order' => 'rank ASC'),
			'sizes'		=> array(self::MANY_MANY, 'PhotoSize', 'event_album_size(album_id, size_id)'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
            $criteria=new CDbCriteria;

            $criteria->compare('id',$this->id,true);
            $criteria->compare('event_id',$this->event_id);
            $criteria->compare('name',$this->name,true);
            $criteria->compare('rank',$this->rank);
            $criteria->compare('state',$this->state);
            $criteria->compare('created',$this->created,true);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
    }

//    public function event()
//    {
//        if (null === $this->_event) {
//            $this->_event = Event::model()->findByPk($this->event_id);
//        }
//
//        return $this->_event;
//    }
	
	public function previewUrl()
	{
		if (null === $this->_previewUrl) {
			$photo = EventPhoto::model()->find(array(
				'condition' => 'album_id=:album_id',
				'params' => array(
					'album_id' => $this->id,
				),
				'order' => 'rank ASC',
				'limit' => 1,
			));
			
			$w = Yii::app()->params['member_area']['album']['preview']['width'];
			$h = Yii::app()->params['member_area']['album']['preview']['height'];
			
			if ($photo) {
				$this->_previewUrl = $photo->previewUrl($w, $h);
			} else {
				$this->_previewUrl = EventPhoto::model()->defaultPicUrl($w, $h);
			}
		}
		
		return $this->_previewUrl;
		
	}
	
//	public function photos()
//	{
//		if (null === $this->_photos) {
//			$this->_photos = EventPhoto::model()->findAll(array(
//				'condition' => 'album_id=:album_id',
//				'params' => array(
//					'album_id' => $this->id,
//				),
//				'order' => 'rank ASC',
//			));
//		}
//		
//		return $this->_photos;
//	}
	
	public function setNextRank()
	{	
		$maxRank = (int) Yii::app()->db->createCommand()
							->select('max(rank) as max')
							->from($this->tableName())
							->where('event_id=:event_id', array(':event_id' => $this->event_id))
							->queryScalar();
		
		$this->rank = $maxRank + 1;
	}
	
	public function isOwner()
	{
		return $this->event->isOwner();
	}
	
	public function setSizes($sizes)
	{
		EventAlbumSize::model()->deleteAll('album_id=:album_id', array(
			':album_id' => $this->id,
		));
		
		foreach ($sizes as $size) {
			$sizeEntry = PhotoSize::model()->findByAttributes(array(
				'id'	=> (int) $size,
				'state' => PhotoSize::STATE_ACTIVE,
			));
			
			if ($sizeEntry) {
				
				$s = new EventAlbumSize();
				$s->setAttributes(array(
					'size_id'  => $sizeEntry->id,
					'album_id' => $this->id,					
				));
				$s->save();
				unset($s);
			}
		}
		
		return $this;
	}
}