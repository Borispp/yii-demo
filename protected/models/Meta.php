<?php

/**
 * This is the model class for table "meta".
 *
 * The followings are the available columns in table 'meta':
 * @property string $id
 * @property string $elm
 * @property integer $elm_id
 * @property string $title
 * @property string $description
 * @property integer $keywords
 */
class Meta extends YsaActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return Meta the static model class
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
        return 'meta';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('elm_id', 'numerical', 'integerOnly'=>true),
            array('elm', 'length', 'max'=>20),
            array('title', 'length', 'max'=>255),
//            array('elm, elm_id', 'required'),
            array('description, keywords', 'safe'),
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
            'elm' => 'Element',
            'elm_id' => 'Element ID',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
        );
    }
    
    public function findByElement($elmId, $elm = 'page')
    {
        return $this->findByAttributes(array(
            'elm_id'    => $elmId,
            'elm'       => $elm,
        ));
    }
}