<?php

/**
 * This is the model class for table "tutorial".
 *
 * The followings are the available columns in table 'tutorial':
 * @property string $id
 * @property integer $cat_id
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property string $created
 * @property string $updated
 * @property integer $rank
 * @property integer $state
 */
class Tutorial extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Tutorial the static model class
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
		return 'tutorial';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id, rank, state', 'numerical', 'integerOnly'=>true),
			array('slug', 'length', 'max'=>100),
			array('title', 'length', 'max'=>255),
			array('content, created, updated', 'safe'),
			
			array('slug, title, cat_id', 'required'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, cat_id, slug, title, content, created, updated, rank, state', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'category'			=> array(self::BELONGS_TO, 'TutorialCategory', 'cat_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cat_id' => 'Cat',
			'slug' => 'Slug',
			'title' => 'Title',
			'content' => 'Content',
			'created' => 'Created',
			'updated' => 'Updated',
			'rank' => 'Rank',
			'state' => 'State',
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
		$criteria->compare('cat_id',$this->cat_id);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getCategories()
	{
		return TutorialCategory::model()->findAll(array(
			'order' => 'rank ASC',
		));
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
							->where('cat_id=:cat_id', array(':cat_id' => $this->cat_id))
							->queryScalar();
		
		$this->rank = $maxRank + 1;
	}
	
	public function generateSlugFromTitle()
	{
		$this->slug = YsaHelpers::filterSystemName($this->title);
	}
}