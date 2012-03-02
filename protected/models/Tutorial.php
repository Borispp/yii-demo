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
 * @property string $video
 * @property string $preview
 * 
 */
class Tutorial extends YsaActiveRecord
{
	protected $_prev;
	
	protected $_next;
	
	protected $_video;
	
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
			array('title, video', 'length', 'max'=>255),
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
			'files'				=> array(self::HAS_MANY, 'TutorialFile', 'tutorial_id', 'order' => 'id ASC'),
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
	
	public function previous()
	{
		if (null === $this->_prev) {
			$this->_prev = $this->model()->find('cat_id=:cat_id AND rank<:rank', array(
				'cat_id' => $this->cat_id,
				'rank'   => $this->rank,
			));
			if (!$this->_prev) {
				$this->_prev = false;
			}
		}
		return $this->_prev;
	}
	
	public function next()
	{
		if (null === $this->_next) {
			$this->_next = $this->model()->find('cat_id=:cat_id AND rank>:rank', array(
				'cat_id' => $this->cat_id,
				'rank'   => $this->rank,
			));
			if (!$this->_next) {
				$this->_next = false;
			}
		}
		return $this->_next;
	}
	
	public function getUploadDir()
	{
		$dir = rtrim(Yii::getPathOfAlias('webroot.images.tutorials'), '/');

		$dir .= DIRECTORY_SEPARATOR . $this->id;

		if (!is_dir($dir)) {
			mkdir($dir);
			chmod($dir, 0777);
		}

		return $dir;
	}

	public function getUploadUrl()
	{
		$url = Yii::app()->getBaseUrl(true) . '/images/tutorials/' . $this->id;

		return $url;
	}
	
	public function uploadFile($name, CUploadedFile $instance)
	{
		
		$file = new TutorialFile();
		$file->tutorial_id = $this->id;
		$file->name = str_replace('.', '_', $name ? $name : $instance->getName());
		$file->mime = $instance->getType();
		$file->ext = YsaHelpers::mimeToExtention($file->mime);
		$file->generateBaseName();
		
		$instance->saveAs($this->getUploadDir() . '/' . $file->basename);
		
		return $file->save();
	}
	
	public function video($width = 640, $height = 360)
	{
		if (null === $this->_video) {
			if ($this->video) {
				$this->_video = YsaHelpers::videoFromLink($this->video, $width, $height);
			} else {
				$this->_video = false;
			}
		}
		
		return $this->_video;
	}
	
	public function previewUrl()
	{
		if ($this->preview) {
			return $this->getUploadUrl() . '/' . $this->preview;
		} else {
			return EventPhoto::model()->defaultPicUrl(Yii::app()->params['tutorial']['preview']['width'], Yii::app()->params['tutorial']['preview']['height']);
		}
	}
	
	public function preview()
	{
		return YsaHtml::image($this->previewUrl(), '');
	}
	
	public function previewPath()
	{
		return $this->getUploadDir() . DIRECTORY_SEPARATOR . $this->preview;
	}
	
	public function uploadPreview($save = false)
	{
		if (! ($this->preview instanceof CUploadedFile)) {
			return false;
		}
		
		$image = new YsaImage($this->preview->getTempName());
		
		$newName = YsaHelpers::encrypt($this->title . $this->id) . '.' . $image->ext;
		$savePath = $this->getUploadDir() . DIRECTORY_SEPARATOR . $newName;
		
		$image->auto_crop(
			Yii::app()->params['tutorial']['preview']['width'], 
			Yii::app()->params['tutorial']['preview']['height']
		);
		
		$image->save($savePath);
		
		$this->preview = $newName;
		
		if ($save) {
			$this->save();
		}
		
		return true;
	}
	
	public function deletePreview($save = false)
	{
		$filename = $this->previewPath();
		if (is_file($filename)) {
			unlink($filename);
		}
		$this->preview = '';
		
		if ($save) {
			$this->save();
		}
		
		return $this;
	}
}