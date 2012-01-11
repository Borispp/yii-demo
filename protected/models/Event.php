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
 * 
 * @property array $albums
 * @property array $user
 */
class Event extends YsaActiveRecord
{
    const TYPE_PUBLIC = 'public';
	
    const TYPE_PROOF  = 'proof';
	
	const TYPE_PORTFOLIO = 'portfolio';
	
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
		return array(
				array('user_id, state, type', 'required'),
				array('user_id, state', 'numerical', 'integerOnly'=>true),
				array('type', 'length', 'max' => 10),
				array('name', 'length', 'max' => 255),
				array('type', 'validateType'),
				array('passwd', 'length', 'max' => 20),
				array('name, user_id, state, type', 'required'),
				array('passwd, description, date, created, updated', 'safe'),
				array('id, user_id, type, name, date, state, created', 'safe', 'on'=>'search'),
		);
    }
	
	/**
	 * Type validator
	 * 
	 * @param string $attr 
	 */
	public function validateType($attr)
	{
		if (!in_array($this->$attr, array_keys($this->getTypes()))) {
			$this->addError($attr, 'You can add only 3 types of events - Portfolio, Public and Proof');
		}
	}

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'albums' => array(self::HAS_MANY, 'EventAlbum', 'event_id', 'order' => 'rank ASC'),
            'user'   => array(self::BELONGS_TO, 'User', 'user_id'),
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
			self::TYPE_PORTFOLIO => 'Portfolio',
            self::TYPE_PUBLIC	 => 'Public',
            self::TYPE_PROOF	 => 'Proof',
        );
    }
    
	/**
	 * Get Event type
	 * 
	 * return string
	 */
    public function type()
    {
        switch ($this->type) {
			case self::TYPE_PORTFOLIO:
				$type = 'Portfolio';
				break;
            case self::TYPE_PUBLIC:
                $type = 'Public';
                break;
            case self::TYPE_PROOF:
                $type = 'Proofing';
                break;
        }
		return $type;
    }
    
	/**
	 * Generate unique Event password
	 */
    public function generatePassword()
    {
        $this->passwd = YsaHelpers::genRandomString(6);
    }
	
	/**
	 * Get search criteria for search panel
	 * 
	 * @return CDbCriteria 
	 */
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
		
		// search by state
		if (isset($type) && $type != '') {
			$criteria->compare('type', $type);
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
		
		foreach ($this->albums as $album) {
			$album->delete();
		}
		
		return true;
	}
	
	public function isOwner()
	{
		return $this->user->id == Yii::app()->user->id;
	}
	
	public function isProofing()
	{
		return self::TYPE_PROOF == $this->type;
	}
	
	public function isPublic()
	{
		return self::TYPE_PUBLIC == $this->type;
	}
	
	public function isPortfolio()
	{
		return self::TYPE_PORTFOLIO == $this->type;
	}
	
	/**
	 * Get search options for search panel
	 * 
	 * @return array 
	 */
	public function searchOptions()
	{
		if (null !== Yii::app()->session[self::SEARCH_SESSION_NAME]) {
			$values = Yii::app()->session[self::SEARCH_SESSION_NAME];
		}
		
		$options = array(
			'keyword' => array(
				'label'     => 'Keyword',
				'type'      => 'text',
				'default'   => '',
				'value'     => isset($values['keyword']) ? $values['keyword'] : '',
			),
			'order_by' => array(
				'label' => 'Order By',
				'type'  => 'select',
				'options' => array(
					'id'    => 'ID',
					'name'  => 'Name',
				),
				'value'     => isset($values['order_by']) ? $values['order_by'] : '',
			),
			'order_sort' => array(
				'label' => 'Order Sort',
				'type'  => 'select',
				'options' => array(
					'ASC'  => 'ASC',
					'DESC' => 'DESC',
				),
				'value'     => isset($values['order_sort']) ? $values['order_sort'] : '',
			),
			'state' => array(
				'label'             => 'State',
				'type'              => 'select',
				'addEmptyOption'    => true,
				'options'           => Event::model()->getStates(),
				'value'     => isset($values['state']) ? $values['state'] : '',
			),
			'type' => array(
				'label'             => 'Type',
				'type'              => 'select',
				'addEmptyOption'    => true,
				'options'           => Event::model()->getTypes(),
				'value'     => isset($values['type']) ? $values['type'] : '',
			),
		);
		
		return $options;
	}
}