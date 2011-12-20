<?php

/**
 * This is the model class for table "option_type".
 *
 * The followings are the available columns in table 'option_type':
 * @property integer $id
 * @property string $name
 */
class OptionType extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OptionType the static model class
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
            return 'option_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
            return array(
                array('name', 'length', 'max'=>100),
            );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
            return array(
                'option' => array(self::HAS_MANY, 'Option', 'type_id'),
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
            );
	}
}