<?php

/**
 * This is the model class for table "user_option".
 *
 * The followings are the available columns in table 'user_option':
 * @property string $id
 * @property integer $studio_id
 * @property string $name
 * @property string $url
 * @property integer $rank
 * @property string $created
 * @property string $updated
 * @property string $friendly_url
 * 
 * @property Studio $studio
 */
class StudioLink extends YsaActiveRecord
{
	protected $_studio;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return StudioLink the static model class
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
		return 'studio_link';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('studio_id, name, url', 'required'),
			array('studio_id, rank', 'numerical', 'integerOnly'=>true),
			array('name, url', 'length', 'max'=>100),
			array('created, updated', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'studio' => array(self::BELONGS_TO, 'Studio', 'studio_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'studio_id' => 'User',
			'name' => 'Name',
			'url' => 'URL',
			'rank' => 'Rank',
			'created' => 'Created',
			'updated' => 'Updated',
		);
	}
	
	public function setNextRank()
	{
		$maxRank = (int) Yii::app()->db->createCommand()
							->select('max(rank) as max')
							->from($this->tableName())
							->where('studio_id=:studio_id', array(':studio_id' => $this->studio_id))
							->queryScalar();
		$this->rank = $maxRank + 1;
	}
	
	public function beforeValidate() 
	{
		$this->friendly_url = $this->url;
		
		return parent::beforeValidate();
	}
	
	public function isOwner()
	{
		return $this->studio->isOwner();
	}
}