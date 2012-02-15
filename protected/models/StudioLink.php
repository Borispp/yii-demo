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
 * @property string $icon
 * @property string $type
 * 
 * @property Studio $studio
 */
class StudioLink extends YsaActiveRecord
{
	protected $_studio;
	
	protected $_iconsUrl;
	
	protected $_iconsPath;
	
	protected $_icons;
	
	const TYPE_BOOKMARK = 'bookmark';
	
	const TYPE_CUSTOM = 'custom';
	
	public function init()
	{
		parent::init();
		
		$this->_iconsPath = rtrim(Yii::getPathOfAlias('webroot.resources.images.icons'), '/');
		$this->_iconsUrl = Yii::app()->getBaseUrl(true) . '/resources/images/icons';
	}
	
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
			array('icon, type', 'safe'),
			array('studio_id, rank', 'numerical', 'integerOnly'=>true),
			array('name, url', 'length', 'max'=>100),
			array('name', 'validateLink'),
			array('created, updated', 'safe'),
		);
	}
	
	public function validateLink($field)
	{
		if ($this->type == self::TYPE_CUSTOM) {
			if (strlen($this->{$field}) > 10) {
				$this->addError($field, 'Custom Link label cannot be more that 10 symbols.');
			}
		}
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
	
	public function iconUrl($folder = 'black')
	{
		
		
		if ($this->icon && is_file($this->_iconsPath . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $this->icon)) {
			$img = $this->_iconsUrl . '/' . $folder . '/' . $this->icon;
		} else {
			$img = ImageHelper::thumb(
				Yii::app()->params['studio_options']['icon']['width'], 
				Yii::app()->params['studio_options']['icon']['height'], 
				ImageHelper::defaultImagePath()
			);
		}
		
		return $img;
	}
	
	public function icon($folder = 'black')
	{
		return YsaHtml::image($this->iconUrl($folder));
	}
	
	public function icons($folder = 'black')
	{
		if (null === $this->_icons) {
			
			$_icons = scandir($this->_iconsPath . '/' . $folder);
			
			$this->_icons = array();
			
			foreach ($_icons as $icon) {
				if (!in_array($icon, array('.', '..', '.DS_Store'))) {
					preg_match('~([^\.]+)(\.png)+~si', $icon, $matches);
					
					$name = $matches[1];
					$title = ucwords(str_replace(array('_', '-'), ' ', $name));
					$url = $this->_iconsUrl . '/' . $folder . '/' . $icon;
					
					$i = new stdClass();
					
					$i->title = $title;
					$i->name = $name;
					$i->url = $url;
					
					$this->_icons[$icon] = $i;
				}
			}
		}
		
		return $this->_icons;
	}
	
	public function getTypes()
	{
		return array(
			self::TYPE_BOOKMARK => 'Bookmark Link',
			self::TYPE_CUSTOM => 'Custom Link',
		);
	}
	
	public function type($type)
	{
		$types = $this->getTypes();
		
		return $types[$type];
	}
}