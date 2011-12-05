<?php

/**
 * This is the model class for table "option".
 *
 * The followings are the available columns in table 'option':
 * @property string $id
 * @property string $name
 * @property string $value
 * @property integer $rank
 * @property integer $type_id
 * @property integer $group_id
 */
class Option extends YsaActiveRecord
{
    const TYPE_TEXT     = 1;
    const TYPE_TEXTAREA = 2;
    const TYPE_DROPDOWN = 3;
    const TYPE_CHECKBOX = 4;
    const TYPE_RADIO    = 5;
    const TYPE_IMAGE    = 6; // TODO

    /**
	 * Returns the static model of the specified AR class.
	 * @return Option the static model class
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
		return 'option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rank, type_id', 'numerical', 'integerOnly'=>true),
                        array('type_id, name, title, group_id', 'required'),
                        array('name', 'unique'),
			array('name', 'length', 'max'=>50),
			array('value, options', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, value, rank, type_id', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'value' => 'Value',
			'rank' => 'Rank',
			'type_id' => 'Type',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('type_id',$this->type_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
    public function findByGroup($id)
    {
        return $this->findAll(array(
            'condition' => 'group_id=:group_id',
            'params'    => array(':group_id' => $id),
        ));
    }
    
    public function renderField()
    {
        $field = null;
        $fieldName = 'id[' . $this->id . ']';
        
        switch ($this->type_id) {
            case self::TYPE_TEXT:
                $field = CHtml::textField($fieldName, $this->value);
                break;
            case self::TYPE_TEXTAREA:
                $field = CHtml::textArea($fieldName, $this->value, array('data-autogrow' => 'true', 'cols' => 80, 'rows' => 3));
                break;
            case self::TYPE_DROPDOWN:
                $field = CHtml::dropDownList($fieldName, $this->value, $this->getOptionOptionsList(true));
                break;
            case self::TYPE_CHECKBOX:
                $field = CHtml::checkBox($fieldName, intval($this->value));
                break;
            case self::TYPE_RADIO:
                $field = CHtml::radioButtonList($fieldName, $this->value, $this->getOptionOptionsList(), array('separator' => ' '));
                break;
        }
        
        return $field;
    }
    
    public function type()
    {
        return OptionType::model()->findByPk($this->type_id);
    }
    
    public function getTypes()
    {
        $entries = OptionType::model()->findAll();
        $types = array();
        foreach ($entries as $entry) {
            $types[$entry->id] = $entry->name;
        }
        return $types;
    }
    
    public function getOptionOptionsList($addEmpty = false)
    {
        $_options = explode(',', $this->options);
        $options = array();
        if ($addEmpty) {
            $options[''] = self::LEVEL . self::LEVEL . self::LEVEL;
        }
        foreach ($_options as $opt) {
            list($val, $name) = explode(':', $opt);
            $options[trim($val)] = trim($name);
        }
        return $options;
    }
}