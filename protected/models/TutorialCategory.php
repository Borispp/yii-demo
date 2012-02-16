<?php

/**
 * This is the model class for table "tutorial_category".
 *
 * The followings are the available columns in table 'tutorial_category':
 * @property string $id
 * @property string $name
 * @property integer $rank
 * @property integer $state
 * @property string $created
 * @property string $updated
 */
class TutorialCategory extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TutorialCategory the static model class
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
		return 'tutorial_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rank, state', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('created, updated', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'tutorials' => array(self::HAS_MANY, 'Tutorial', 'cat_id', 'order' => 'rank ASC'),
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
			'rank' => 'Rank',
			'state' => 'State',
			'created' => 'Created',
			'updated' => 'Updated',
		);
	}
	
	public function beforeSave() 
	{
		parent::beforeSave();
		if($this->isNewRecord) {
			$this->setNextRank();
		}
		return true;
	}
	
	public function setNextRank()
	{
		$maxRank = (int) Yii::app()->db->createCommand()
							->select('max(rank) as max')
							->from($this->tableName())
							->queryScalar();
		
		$this->rank = $maxRank + 1;
	}
}