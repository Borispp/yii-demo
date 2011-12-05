<?php

/**
 * This is the model class for table "option_group".
 *
 * The followings are the available columns in table 'option_group':
 * @property string $id
 * @property string $title
 * @property integer $rank
 * @property string $slug
 * @property integer $hidden
 */
class OptionGroup extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OptionGroup the static model class
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
		return 'option_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rank, hidden', 'numerical', 'integerOnly'=>true),
			array('title, slug', 'length', 'max'=>100),
                        array('slug', 'unique'),
                        array('slug, title', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
//			array('id, title, rank', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'rank' => 'Rank',
                        'hidden' => 'Hidden',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
//	public function search()
//	{
//		// Warning: Please modify the following code to remove attributes that
//		// should not be searched.
//
//		$criteria=new CDbCriteria;
//
//		$criteria->compare('id',$this->id,true);
//		$criteria->compare('title',$this->title,true);
//		$criteria->compare('rank',$this->rank);
//
//		return new CActiveDataProvider($this, array(
//			'criteria'=>$criteria,
//		));
//	}
        
    public function getGroupsList()
    {
        $q = Yii::app()->db
                       ->createCommand()
                       ->select('id AS title')
                       ->order(array('rank ASC'))
                       ->from(Page::tableName());

        return $q->queryColumn();
    }

    public function getNavigationList()
    {
        $groups = $this->model()->findAll(array(
            'order' => 'rank ASC',
        ));

        $list = array();
        foreach ($groups as $group) {
            $list[] = array(
                'label' => $group->title,
                'url'   => array('/admin/settings/group/' . $group->slug),
                'linkOptions' => array('class' => ''), 
            );
        }

        return $list;
    }
        
    public function findBySlug($slug)
    {
        return $this->model()->findByAttributes(array('slug' => $slug));
    }
    
    public function options()
    {
        return Option::model()->findByGroup($this->id);
    }
    
    public function generateSlugFromTitle()
    {
        $this->slug = YsaHelpers::filterSystemName($this->title);
    }
}