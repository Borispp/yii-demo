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
                    array('user_id', 'required'),
                    array('user_id, state', 'numerical', 'integerOnly'=>true),
                    array('type', 'length', 'max'=>6),
                    array('name', 'length', 'max'=>255),
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
        
    protected function beforeValidate()
    {
	if($this->isNewRecord) {
	    $this->created = $this->updated = new CDbExpression('NOW()');
	}
        
	return parent::beforeValidate();
    }
    
    public function albums()
    {
        
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
}