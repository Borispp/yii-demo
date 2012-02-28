<?php

/**
 * This is the model class for table "tutorial_file".
 *
 * The followings are the available columns in table 'tutorial_file':
 * @property string $id
 * @property integer $tutorial_id
 * @property string $name
 * @property string $file
 * @property string $mime
 * @property string $ext
 * @property string $created
 * @property string $updated
 * @property string $basename
 * @property Tutorial $tutorial
 */
class TutorialFile extends YsaActiveRecord
{
	protected $_file;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return TutorialFile the static model class
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
		return 'tutorial_file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tutorial_id', 'required'),
			array('tutorial_id', 'numerical', 'integerOnly'=>true),
			array('name, basename', 'length', 'max'=>100),
			array('file', 'length', 'max'=>255),
			array('mime', 'length', 'max'=>20),
			array('ext', 'length', 'max'=>5),
			array('created, updated', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'tutorial' => array(self::BELONGS_TO, 'Tutorial', 'tutorial_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tutorial_id' => 'Tutorial',
			'name' => 'Name',
			'file' => 'File',
			'mime' => 'Mime',
			'ext' => 'Ext',
			'created' => 'Created',
			'updated' => 'Updated',
		);
	}
	
	public function generateBaseName()
	{
		$this->basename = YsaHelpers::encrypt(microtime() . $this->name . $this->tutorial_id);
	}
	
	public function file()
	{
		if (null === $this->_file) {
			$this->_file = $this->tutorial->getUploadDir() . DIRECTORY_SEPARATOR . $this->basename;
		}
		return $this->_file;
	}
	
	public function url()
	{
		return Yii::app()->createAbsoluteUrl('tutorial/download/' . $this->basename);
	}
	
	/**
	 * Remove file before entity
	 * 
	 * @return boolean 
	 */
	public function beforeDelete() {
		parent::beforeDelete();
		
		if (is_file($this->file())) {
			unlink($this->file());
		}
		
		return true;
	}
}