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
 * @property string $options
 */
class Option extends YsaOptionActiveRecord
{
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
        return array(
            'group' => array(self::HAS_ONE, 'OptionGroup', 'group_id'),
            'type'  => array(self::HAS_ONE, 'OptionGroup', 'type_id'),
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
    
    public function image()
    {
        if (!$this->_image) {
            $this->_image = OptionImage::model()->findByElement($this->id, $this->tableName());
            if (null === $this->_image) {
                $this->_image = new OptionImage();
                $this->_image->elm = $this->tableName();
                $this->_image->elm_id = $this->id;
            }
        }
        
        return $this->_image;
    }
}