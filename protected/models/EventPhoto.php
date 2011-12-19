<?php

/**
 * This is the model class for table "event_album".
 *
 * The followings are the available columns in table 'event_album':
 * @property integer $id
 * @property integer $album_id
 * @property string $basename
 * @property string $extention
 * @property string $meta_type
 * @property string $alt
 * @property integer $state
 * @property integer $rank
 * @property string $created
 * @property string $updated
 */
class EventPhoto extends YsaActiveRecord
{
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
			array('album_id, rank, state', 'numerical', 'integerOnly'=>true),
			array('album_id, basename, extention', 'required'),
			array('basename', 'length', 'max'=>100),
			array('extention', 'length', 'max'=>5),
			array('meta_type', 'length', 'max'=>20),
			array('alt', 'length', 'max'=>255),
			array('meta_type, alt, rank, created, updated', 'safe'),
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


    public function album()
    {
        if (null === $this->_album) {
            $this->_album = EventAlbum::model()->findByPk($this->album_id);
        }

        return $this->_album;
    }
	
	public function preview()
	{
		return 'preview';
	}
}