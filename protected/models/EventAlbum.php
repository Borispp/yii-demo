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
 */
class EventAlbum extends YsaActiveRecord
{
    const PROOFING_NAME = 'Proofing Album';
    
    protected $_event;
    
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
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                    array('event_id, rank, state', 'numerical', 'integerOnly'=>true),
                    array('name, place', 'length', 'max'=>255),
                    array('description, shooting_date, created, updated', 'safe'),
                    // The following rule is used by search().
                    // Please remove those attributes that should not be searched.
                    array('id, event_id, name, description, state, created', 'safe', 'on'=>'search'),
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
            'event'  => array(self::BELONGS_TO, 'Event', 'event_id'),
            'photo'  => array(self::HAS_MANY, 'EventPhoto', 'album_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
            return array(
                'id' => 'ID',
                'event_id' => 'Event',
                'name' => 'Name',
                'description' => 'Description',
                'shooting_date' => 'Shooting Date',
                'place' => 'Place',
                'rank' => 'Rank',
                'state' => 'State',
                'created' => 'Created',
                'updated' => 'Updated',
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
            $criteria->compare('event_id',$this->event_id);
            $criteria->compare('name',$this->name,true);
            $criteria->compare('description',$this->description,true);
            $criteria->compare('shooting_date',$this->shooting_date,true);
            $criteria->compare('place',$this->place,true);
            $criteria->compare('rank',$this->rank);
            $criteria->compare('state',$this->state);
            $criteria->compare('created',$this->created,true);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
    }

    public function event()
    {
        if (null === $this->_event) {
            $this->_event = Event::model()->findByPk($this->event_id);
        }

        return $this->_event;
    }
	
	public function preview()
	{
		return 'preview';
	}
	
	public function beforeDelete() {
		parent::beforeDelete();
		
		// delete all pictures
		
		
		return true;
	}
}