<?php

/**
 * This is the model class for table "portfolio_album".
 *
 * The followings are the available columns in table 'portfolio_album':
 * @property string $id
 * @property integer $portfolio_id
 * @property string $name
 * @property integer $rank
 * @property string $created
 * @property string $updated
 */
class PortfolioAlbum extends YsaActiveRecord
{
	protected $_photos;
	
    protected $_uploadPath;
    
    protected $_uploadUrl;
	
	protected $_preview;
	
	protected $_previewUrl;
	
    public function init() {
        parent::init();
        
        $this->_uploadPath = rtrim(Yii::getPathOfAlias('webroot.images.portfolio.albums'), '/');
        $this->_uploadUrl = Yii::app()->getBaseUrl(true) . '/images/portfolio/albums';
    }
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return PortfolioAlbum the static model class
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
		return 'portfolio_album';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('portfolio_id, rank, state', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('created, updated, description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, portfolio_id, name, rank, created, updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'portfolio' => array(self::BELONGS_TO, 'Portfolio', 'portfolio_id'),
			'photo'		=> array(self::HAS_MANY, 'PortfolioPhoto', 'album_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'portfolio_id' => 'Portfolio',
			'title' => 'Title',
			'rank' => 'Rank',
			'created' => 'Created',
			'updated' => 'Updated',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('portfolio_id',$this->portfolio_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function preview($htmlOptions = array())
	{
		if (null === $this->_preview) {
			$this->_preview = YsaHtml::image($this->previewUrl(), 'Album Preview', $htmlOptions);
		}
		return $this->_preview;
	}
	
	public function previewUrl()
	{
		if (null === $this->_previewUrl) {
			$photo = PortfolioPhoto::model()->find(array(
				'condition' => 'album_id=:album_id',
				'params' => array(
					'album_id' => $this->id,
				),
				'order' => 'rank ASC',
				'limit' => 1,
			));
			
			$w = Yii::app()->params['member_area']['album']['preview']['width'];
			$h = Yii::app()->params['member_area']['album']['preview']['height'];
			
			if ($photo) {
				$this->_previewUrl = $photo->previewUrl($w, $h);
			} else {
				$this->_previewUrl = EventPhoto::model()->defaultPicUrl($w, $h);
			}
		}
		
		return $this->_previewUrl;
		
	}
	
	/**
	 * Delete all photos with album
	 * @return bool
	 */
	public function beforeDelete() {
		parent::beforeDelete();
		
		$photos = PortfolioPhoto::model()->findAll(array(
			'condition' => 'album_id=:album_id',
			'params' => array(
				'album_id' => $this->id,
			),
		));
		
		foreach ($photos as $p) {
			$p->delete();
		}
		
		return true;
	}
	
	/**
	 * Set next rank for event album
	 * @return bool
	 */
    public function beforeSave() 
	{
        if($this->isNewRecord) {
            $this->setNextRank();
        }
        return parent::beforeValidate();
    }
	
	public function encryptedId()
	{
		return YsaHelpers::encrypt($this->id);
	}
	
	public function albumPath()
	{
		$folder = $this->_uploadPath . DIRECTORY_SEPARATOR . $this->encryptedId();
		
		if (!is_dir($folder)) {
			mkdir($folder, 0777);
		}
		
		return $folder;
	}
	
	public function albumUrl()
	{
		return $this->_uploadUrl . '/' . $this->encryptedId();
	}
	
	public function photos()
	{
		if (null === $this->_photos) {
			$this->_photos = PortfolioPhoto::model()->findAll(array(
				'condition' => 'album_id=:album_id',
				'params' => array(
					'album_id' => $this->id,
				),
				'order' => 'rank ASC',
			));
		}
		
		return $this->_photos;
	}
	
	public function setNextRank()
	{	
		$maxRank = (int) Yii::app()->db->createCommand()
							->select('max(rank) as max')
							->from($this->tableName())
							->where('portfolio_id=:portfolio_id', array(':portfolio_id' => $this->portfolio_id))
							->queryScalar();
		
		$this->rank = $maxRank + 1;
	}
}