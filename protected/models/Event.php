<?php

/**
 * This is the model class for table "event".
 *
 * The followings are the available columns in table 'event':
 * @property string $id
 * @property integer $user_id
 * @property string $type
 * @property string $name
 * @property string $description
 * @property string $date
 * @property integer $state
 * @property string $created
 * @property string $updated
 * @property string $passwd
 */
class Event extends YsaActiveRecord
{
    const TYPE_PUBLIC = 'public';
    const TYPE_PROOF  = 'proof';

    /**
     * Returns the static model of the specified AR class.
     * @return Event the static model class
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
        return 'event';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                    array('user_id, state, type', 'required'),
                    array('user_id, state', 'numerical', 'integerOnly'=>true),
                    array('type', 'length', 'max'=>6),
                    array('name', 'length', 'max'=>255),
                    array('passwd', 'length', 'max'=>20),
                    array('description, date, created, updated', 'safe'),
                    // The following rule is used by search().
                    // Please remove those attributes that should not be searched.
                    array('id, user_id, type, name, date, state, created', 'safe', 'on'=>'search'),
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
            'album' => array(self::HAS_MANY, 'EventAlbum', 'event_id'),
            'user'  => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'type' => 'Type',
            'name' => 'Name',
            'description' => 'Description',
            'date' => 'Event Date',
            'state' => 'State',
            'created' => 'Created',
            'updated' => 'Updated',
            'passwd'    => 'Password',
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
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('date',$this->date,true);
        $criteria->compare('state',$this->state);
        $criteria->compare('created',$this->created,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Get Event Types
     * @return array
     */
    public function getTypes()
    {
        return array(
            self::TYPE_PUBLIC => 'Public',
            self::TYPE_PROOF  => 'Proof',
        );
    }
    
    public function albums()
    {
        return EventAlbum::model()->findAll(array(
			'condition' => 'event_id=:event_id',
			'params'    => array(
				':event_id' => $this->id,
			),
			'order' => 'rank ASC',
		));
    }
    
    public function type()
    {
        switch ($this->type) {
            case self::TYPE_PUBLIC:
                return 'Public';
                break;
            case self::TYPE_PROOF:
                return 'Proofing';
                break;
        }
    }
    
    public function generatePassword()
    {
        $this->passwd = YsaHelpers::genRandomString(6);
    }
	
	public function searchCriteria()
	{
		$criteria = new CDbCriteria();
		
		$fields = Yii::app()->session[self::SEARCH_SESSION_NAME];
		
		if (null === $fields) {
			return $criteria;
		}
		
		extract($fields);
		
		// search by keyword
		if (isset($keyword) && $keyword) {
			$criteria->compare('name', $keyword, true);
		}
		
		// search by state
		if (isset($state) && $state != '') {
			$criteria->compare('state', $state);
		}
		
		// sort entries
		if (isset($order_by) && isset($order_sort)) {
			if (!in_array($fields['order_by'], array_keys($this->attributes))) {
				$order_by = 'id';
			}
			
			if (!in_array($order_sort, array('ASC', 'DESC'))) {
				$order_sort = 'DESC';
			}
			
			$criteria->order = $order_by . ' ' . $order_sort;
			
		}
		
		return $criteria;
	}
	
	/**
	 * remove all albums on delete
	 * @return bool
	 */
	public function beforeDelete() {
		parent::beforeDelete();
		
		foreach ($this->albums() as $album) {
			$album->delete();
		}
		
		return true;
	}
}