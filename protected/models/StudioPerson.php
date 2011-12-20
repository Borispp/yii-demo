<?php

/**
 * This is the model class for table "user_option".
 *
 * The followings are the available columns in table 'user_option':
 * @property string $id
 * @property integer $studio_id
 * @property string $name
 * @property string $photo
 * @property integer $rank
 * @property string $created
 * @property string $updated
 * @property string $friendly_url
 */
class StudioPerson extends YsaActiveRecord
{
	protected $_uploadPath;
	
	protected $_uploadUrl;
	
    public function init() {
        parent::init();
        
        $this->_uploadPath = rtrim(Yii::getPathOfAlias('webroot.images.studio'), '/');
        $this->_uploadUrl = Yii::app()->getBaseUrl(true) . '/images/studio';
    }
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return StudioPerson the static model class
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
		return 'studio_person';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('studio_id', 'required'),
			array('studio_id, rank', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('photo', 'file', 'types'=>'jpg, gif, png', 'maxSize'=> Yii::app()->params['max_image_size'], 'tooLarge'=>'The file was larger than 5MB Please upload a smaller file.', 'allowEmpty' => true),
			array('created, updated', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'studio_id'),
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
			'photo' => 'Photo',
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
	
	public function uploadPhoto($save = false)
	{
		if (! ($this->photo instanceof CUploadedFile)) {
			return false;
		}
		
		$image = new Image($this->photo->getTempName());
		
		$image->quality(100);
		$image->resize(
			Yii::app()->params['studio']['person']['photo']['width'], 
			Yii::app()->params['studio']['person']['photo']['height']
		);
		
        $newName = YsaHelpers::encrypt($this->name . $this->id) . '.' . $image->ext;
        
        $savePath = $this->_uploadPath . DIRECTORY_SEPARATOR . $newName;
        
		if (!is_dir($this->_uploadPath)) {
			mkdir($this->_uploadPath, 0777);
		}
		
        $url = $this->_uploadUrl . '/' . $newName;
		
		$this->photo = $newName;
		
		if ($save) {
			$this->save();
		}
		
		return true;
	}
}