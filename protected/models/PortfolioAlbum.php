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
 * @property integer $cover_id
 * 
 * Relations
 * @property Portfolio $portfolio
 * @property PortfolioPhoto $photos
 */
class PortfolioAlbum extends YsaAlbumActiveRecord
{
	protected $_portfolio;

    public function init() {
        parent::init();
        
        $this->_uploadPath = rtrim(Yii::getPathOfAlias('webroot.images.portfolio.albums'), '/');
        $this->_uploadUrl = Yii::app()->getBaseUrl(true) . '/images/portfolio/albums';
		$this->_createDir();
    }
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return PortfolioAlbum the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
		Yii::app()->getRequest()->getBaseUrl();
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
		return array(
			array('portfolio_id, rank, state', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('portfolio_id, state, name', 'required'),
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
			'photos'	=> array(self::HAS_MANY, 'PortfolioPhoto', 'album_id'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function previewUrl()
	{
		$photo = $this->cover();

		$w = Yii::app()->params['member_area']['album']['preview']['width'];
		$h = Yii::app()->params['member_area']['album']['preview']['height'];

		if ($photo) {
			$previewUrl = $photo->previewUrl($w, $h);
		} else {
			$previewUrl = PortfolioPhoto::model()->defaultPicUrl($w, $h);
		}
		
		return $previewUrl;
	}
	
	public function cover()
	{
		if (null === $this->_cover) {
			
			$photo = PortfolioPhoto::model()->findByPk($this->cover_id);
			
			if (!$photo) {
				$photo = PortfolioPhoto::model()->find(array(
					'condition' => 'album_id=:album_id',
					'params' => array(
						'album_id' => $this->id,
					),
					'order' => 'rank ASC',
					'limit' => 1,
				));
			}
			
			$this->_cover = $photo;
		}
		
		return $this->_cover;
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
	
	public function isOwner()
	{
		return $this->portfolio->isOwner();
	}
	
	public function changeCover($not = 0)
	{
		$photo = PortfolioPhoto::model()->find(array(
			'condition' => 'id<>:not AND album_id=:album_id',
			'params' => array(
				'album_id' => $this->id,
				'not' => (int) $not,
			),
			'order' => 'rank ASC',
			'limit' => 1,
		));
		
		if ($photo) {
			$this->cover_id = $photo->id;
			$this->save();
		}
		
		return $this;
	}
}