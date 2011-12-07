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
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'option' => array(self::HAS_MANY, 'Option', 'group_id'),
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
        
    public function getNavigationList()
    {
        $groups = $this->model()->findAll(array(
            'order' => 'id ASC',
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