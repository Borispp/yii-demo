<?php

/**
 * This is the model class for table "page_custom".
 *
 * The followings are the available columns in table 'page_custom':
 * @property string $id
 * @property integer $page_id
 * @property string $name
 * @property string $image
 * @property string $value
 * 
 * @property Page $page
 */
class PageCustom extends YsaActiveRecord
{
    protected $_uploadPath;
    
    protected $_uploadUrl;
	
    public function init() {
        parent::init();
        
        $this->_uploadPath = rtrim(Yii::getPathOfAlias('webroot.images.pagecustom'), '/');
        $this->_uploadUrl = Yii::app()->getBaseUrl(true) . '/images/pagecustom';
		if (!is_dir($this->_uploadPath)) {
			mkdir($this->_uploadPath);
		}
    }
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'page_custom';
	}

	public function rules()
	{
		return array(
			array('page_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('value, image', 'safe'),
		);
	}

	public function relations()
	{
		return array(
			'page' => array(self::BELONGS_TO, 'Page', 'page_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'page_id' => 'Page',
			'name' => 'Name',
			'image' => 'Image',
			'value' => 'Value',
		);
	}
	
	public function value()
	{
		return nl2br($this->value);
	}
	
	/**
	 * Apply next rank to new album
	 */
	public function setNextRank()
	{
		$maxRank = (int) Yii::app()->db->createCommand()
							->select('max(rank) as max')
							->from($this->tableName())
							->where('page_id=:page_id', array(':page_id' => $this->page_id))
							->queryScalar();
		
		$this->rank = $maxRank + 1;
	}

	public function beforeSave() 
	{
		parent::beforeSave();
		if($this->isNewRecord) {
			$this->setNextRank();
		}
		return true;
	}
	
    public function upload($name, $save = true)
    {   
        $image = new YsaImage($_FILES[$name]['tmp_name']);
        $newName = md5($name . Yii::app()->params['salt']) . '.' . $image->ext;
        $savePath = $this->_uploadPath . DIRECTORY_SEPARATOR . $newName;
        $url = $this->_uploadUrl . '/' . $newName;
        $status = $image->save($savePath);
        
        if ($status) {
            $value = array(
				'url'		=> $url,
                'file'      => $savePath,
                'width'     => $image->width,
                'height'    => $image->height,
                'type'      => $image->type,
                'ext'       => $image->ext,
                'mime'      => $image->mime,
            );
			$this->image = serialize($value);
        } else {
			$this->image = '';
		}
		
		
		if ($save) {
			$this->save();
		}
		
        return $this;
    }
	
	public function image()
	{
		if (YsaHelpers::isSerialized($this->image)) {
			return unserialize($this->image);
		} else {
			return false;
		}
	}
	
	public function deleteImage()
	{
		$image = $this->image();
		
		if ($image) {
			if (is_file($image['file'])) {
				unlink($image['file']);
			}
		}
		$this->image = '';
		$this->save();
		
		return $this;
	}
	
	public function beforeDelete() {
		parent::beforeDelete();
		$this->deleteImage();
		return true;
	}
}