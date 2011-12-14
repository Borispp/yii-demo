<?php

/**
 * This is the model class for table "application_option".
 *
 * The followings are the available columns in table 'application_option':
 * @property string $id
 * @property integer $app_id
 * @property string $name
 * @property string $value
 * @property integer $type_id
 * @property string $created
 * @property string $updated
 */
class ApplicationOption extends YsaActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return ApplicationOption the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'application_option';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
                array('app_id, name', 'required'),
                array('app_id', 'numerical', 'integerOnly'=>true),
                array('name', 'length', 'max'=>100),
                array('value', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'app_id' => 'App',
            'name' => 'Name',
            'value' => 'Value',
            'created' => 'Created',
            'updated' => 'Updated',
        );
    }
    
    public function value()
    {
        return YsaHelpers::isSerialized($this->value) ? unserialize($this->value) : $this->value;
    }
}