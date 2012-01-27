<?php

/**
 * This is the model class for table "translation_category".
 *
 * The followings are the available columns in table 'translation_category':
 * @property string $id
 * @property string $name
 */
class TranslationCategory extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TranslationCategory the static model class
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
		return 'translation_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'length', 'max'=>50),
			array('name', 'required'),
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
	
    public function getNavigationList()
    {
        $categories = $this->model()->findAll(array(
            'order' => 'name ASC',
        ));
        
        $list = array();
        foreach ($categories as $cat) {
            $list[] = array(
                'label' => ucfirst($cat->name),
                'url'   => array('/admin/translation/index/cat/' . $cat->name),
                'linkOptions' => array('class' => Yii::app()->request->getParam('cat') == $cat->name ? 'active' : ''), 
            );
        }

        return $list;
    }
}