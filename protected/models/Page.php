<?php

/**
 * This is the model class for table "page".
 *
 * The followings are the available columns in table 'page':
 * @property string $id
 * @property integer $parent_id
 * @property string $slug
 * @property string $title
 * @property string $short
 * @property string $content
 * @property integer $rank
 * @property string $type
 * @property string $created
 * @property string $updated
 * @property integer $state
 * @property integer $level
 * @property array @items;
 */
class Page extends YsaActiveRecord
{
    const TYPE_SYSTEM  = 'system';
    const TYPE_GENERAL = 'general';
    
    public $items = array();
    
    public $level = 0;
    
    protected $_meta;

    /**
     * Returns the static model of the specified AR class.
     * @return Page the static model class
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
        return 'page';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('parent_id, rank, state', 'numerical', 'integerOnly'=>true),
            array('slug', 'length', 'max'=>100),
            array('title', 'length', 'max'=>255),
            array('type', 'length', 'max'=>10),
            array('short, content, created, updated', 'safe'),
            array('title, type, state, slug', 'required'),
            array('slug', 'unique'),
            array('slug', 'match', 'pattern' => '~[\d\w\-]+~si'),
            array('id, parent_id, slug, title, short, content, rank, type, created, updated, state', 'safe', 'on'=>'search'),
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
                    'parent_id' => 'Parent',
                    'slug' => 'Slug',
                    'title' => 'Title',
                    'short' => 'Short',
                    'content' => 'Content',
                    'rank' => 'Rank',
                    'type' => 'Type',
                    'created' => 'Created',
                    'updated' => 'Updated',
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
            $criteria->compare('parent_id',$this->parent_id);
            $criteria->compare('slug',$this->slug,true);
            $criteria->compare('title',$this->title,true);
            $criteria->compare('short',$this->short,true);
            $criteria->compare('content',$this->content,true);
            $criteria->compare('type',$this->type,true);
            $criteria->compare('state',$this->state);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
    }

    public function getTypes()
    {
        return array(
            self::TYPE_SYSTEM  => 'System',
            self::TYPE_GENERAL => 'General',
        );
    }
    
    public function type()
    {
        switch ($this->type) {
            case self::TYPE_GENERAL:
                return 'General';
                break;
            case self::TYPE_SYSTEM:
                return 'System';
                break;
        }
    }

    public function generateSlugFromTitle()
    {
        $this->slug = YsaHelpers::filterSystemName($this->title);
    }
    
    public function getTree($parent = 0)
    {
        $entries = $this->findAll('parent_id=:id', array(
            'id'    => $parent
        ));
        foreach ($entries as &$entry) {
            $entry->items = $this->getTree($entry->id);
        }
        return $entries;
    }
    
    public function getOneLevelTree($parent = 0, $list = array(), $level = '')
    {
        $entries = $this->findAll(array(
            'order'     => 'id ASC',
            'condition' => 'parent_id=:parent_id',
            'params'    => array(':parent_id' => $parent)
        ));
        
        foreach ($entries as $node) {
            $node->title = ($level ? $level . ' ' : '') . $node->title;
            $list[] = $node;
            $currentLevel = $level;
            $level .= self::LEVEL;
            $list = $this->getOneLevelTree($node->id, $list, $level);
            $level = $currentLevel;
        }
        
        return $list;
    }
    
    public function getListTree($exclude = false, $parent = 0, $list = array(0 => 'Top Level'), $level = '')
    {
        $entries = $this->findAll('parent_id=:id', array(
            'id'    => $parent
        ));
        
        foreach ($entries as $node) {
            if ($exclude != $node->id) {
                $list[$node->id] = $level . ' ' .  $node->title;
                $currentLevel = $level;
                $level .= self::LEVEL;
                $list = $this->getListTree($exclude, $node->id, $list, $level);
                $level = $currentLevel;
            }
        }
        return $list;
    }
    
    /**
     * Get pages slugs
     * @param string $type
     * @return array 
     */
    public function getSlugs($type = '')
    {
        $q = Yii::app()->db
                       ->createCommand()
                       ->select('slug')
                       ->from(Page::tableName());
        
        if ($type) {
           $q->where('type=:type', array(
               ':type' => $type,
           ));
        }
        
        return $q->queryColumn();
    }
    
    public function findBySlug($slug)
    {
        return $this->model()->findByAttributes(array('slug' => $slug));
    }
    
    public function meta()
    {
        if (!$this->_meta) {
            $this->_meta = Meta::model()->findByElement($this->id, 'page');
            if (null === $this->_meta) {
                $this->_meta = new Meta();
                $this->_meta->elm = 'page';
                $this->_meta->elm_id = $this->id;
            }
        }
        
        return $this->_meta;
    }
    
    // delete page meta with page
    public function delete() {
        $this->meta()->delete();
        parent::delete();
    }
}