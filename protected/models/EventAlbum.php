<?php
/**
 * This is the model class for table "event_album".
 *
 * The followings are the available columns in table 'event_album':
 * @property string $id
 * @property integer $event_id
 * @property string $name
 * @property string $description
 * @property string $shooting_date
 * @property string $place
 * @property integer $rank
 * @property integer $state
 * @property string $created
 * @property string $updated
 * @property integer $can_order
 * @property integer $can_share
 * @property integer $cover_id
 * @property string $order_link
 * 
 * @property EventPhoto $photos
 * @property Event $event
 */
class EventAlbum extends YsaActiveRecord
{
	/**
	 * Default name for proofing album 
	 */
	const PROOFING_NAME = 'Proofing Album';
	
	/**
	 * Album Upload path
	 * @var string
	 */
	protected $_uploadPath;
	
	/**
	 * Album Upload Url path
	 * 
	 * @var string
	 */
	protected $_uploadUrl;
	
	/**
	 * Preview image
	 * 
	 * @var string 
	 */
	protected $_preview;
	
	/**
	 * Preview image url
	 * 
	 * @var string
	 */
	protected $_previewUrl;
	
	/**
	 * Album size
	 * 
	 * @var int
	 */
	protected $_size;
	
	/**
	 * Album hash
	 * 
	 * @var string
	 */
	protected $_hash;
	
	/**
	 * Album cover image
	 * 
	 * @var string
	 */
	protected $_cover;
	
	/**
	 * Path where stores original images
	 *
	 * @var type string
	 */
	protected $_originUploadPath;
        
	public function init() 
	{
		parent::init();
		
		$this->_uploadPath = rtrim(Yii::getPathOfAlias('webroot.images.albums'), '/');
        $this->_originUploadPath = rtrim(Yii::getPathOfAlias('webroot.images.original.albums'), '/');
		$this->_uploadUrl = Yii::app()->getBaseUrl(true) . '/images/albums';
		$this->_createDir();
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return EventAlbum the static model class
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
		return 'event_album';
	}
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'event_id' => 'Event',
			'portfolio_id' => 'Portfolio',
			'name' => 'Name',
			'description' => 'Description',
			'shooting_date' => 'Shooting Date',
			'place' => 'Place',
			'rank' => 'Rank',
			'state' => 'State',
			'created' => 'Created',
			'updated' => 'Updated',
			'can_order' => 'Available for order',
			'can_share'	=> 'Available for share',
			'order_link' => 'Order Link'
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('event_id, rank, state, can_share, can_order', 'numerical', 'integerOnly'=>true),
			array('name, place', 'length', 'max' => 255),
			array('event_id, state, name', 'required'),
			array('description, shooting_date, created, updated, can_share, can_order, order_link', 'safe'),
			array('id, event_id, name, state, created', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'event'		=> array(self::BELONGS_TO, 'Event', 'event_id'),
			'photos'	=> array(self::HAS_MANY, 'EventPhoto', 'album_id', 'order' => 'rank ASC'),
			'sizes'		=> array(self::MANY_MANY, 'PhotoSize', 'event_album_size(album_id, size_id)'),
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
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('state',$this->state);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	/**
	 * Delete all photos with album
	 * @return bool
	 */
	public function beforeDelete() {
		parent::beforeDelete();

		foreach ($this->photos as $p) {
			$p->delete();
		}
		
		rmdir($this->albumPath());
		
		return true;
	}
	
	/**
	 * Set next rank for event album
	 * @return bool
	 */
	public function beforeSave() 
	{
		parent::beforeSave();
		if($this->isNewRecord) {
			$this->setNextRank();
		}
		return true;
	}
	
	/**
	 * Album URL
	 * 
	 * @return string
	 */
	public function albumUrl()
	{
		return $this->_uploadUrl . '/' . $this->encryptedId();
	}
	
	/**
	 * Album filesystem path
	 * 
	 * @return string 
	 */
	public function albumPath()
	{
		$folder = $this->_uploadPath . DIRECTORY_SEPARATOR . $this->encryptedId();
		
		if (!is_dir($folder)) {
			mkdir($folder, 0777);
		}
		
		return $folder;
	}
	
	/**
	 * Album sharing option
	 * 
	 * @return bool
	 */
	public function canShare()
	{
		return $this->can_share;
	}
	
	/**
	 * Album order option
	 * 
	 * @return bool
	 */
	public function canOrder()
	{
		return $this->can_order;
	}
	
	public function preview($htmlOptions = array())
	{
		if (null === $this->_preview) {
			$this->_preview = YsaHtml::image($this->previewUrl(), 'Album Preview', $htmlOptions);
		}
		return $this->_preview;
	}

	/**
	 * @param string $hash
	 * @return bool
	 */
	public function checkHash($hash)
	{
		return $this->getChecksum() == $hash;
	}

	/**
	 * Calculates size of all album photos
	 * @return integer
	 */
	public function size()
	{
		if (!$this->_size)
		{
			foreach ($this->photos as $obPortfolioPhoto)
				$this->_size += $obPortfolioPhoto->size;
		}
		return $this->_size;
	}

	/**
	 * Calculates hash with use of each photo hash
	 * @return string
	 */
	public function getChecksum()
	{
		if (!$this->_hash) {
			$hash = array();
			foreach ($this->photos as $obPortfolioPhoto)
				$hash[] = $obPortfolioPhoto->getChecksum();
			sort($hash, SORT_STRING);
			$this->_hash = md5(implode('', $hash));
		}
		return $this->_hash;
	}

	/**
	 * Album preview URL
	 * 
	 * @return string 
	 */
	public function previewUrl()
	{
		$photo = $this->cover();
		
		$w = Yii::app()->params['member_area']['album']['preview']['width'];
		$h = Yii::app()->params['member_area']['album']['preview']['height'];

		if ($photo) {
			$previewUrl = $photo->previewUrl($w, $h);
		} else {
			$previewUrl = EventPhoto::model()->defaultPicUrl($w, $h);
		}
		
		return $previewUrl;
	}
	
	/**
	 * Album cover
	 * 
	 * @return EventPhoto
	 */
	public function cover()
	{
		if (null === $this->_cover) {
			
			$photo = EventPhoto::model()->findByPk($this->cover_id);
			
			if (!$photo) {
				$photo = EventPhoto::model()->find(array(
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

	/**
	 * Apply next rank to new album
	 */
	public function setNextRank()
	{	
		$maxRank = (int) Yii::app()->db->createCommand()
							->select('max(rank) as max')
							->from($this->tableName())
							->where('event_id=:event_id', array(':event_id' => $this->event_id))
							->queryScalar();
		
		$this->rank = $maxRank + 1;
	}
	
	/**
	 * Check is loggged in member is the album owner
	 * 
	 * @return bool
	 */
	public function isOwner()
	{
		return $this->event->isOwner();
	}
	
	/**
	 * Set album sizes
	 * 
	 * @param array $sizes
	 * 
	 * @return EventAlbum 
	 */
	public function setSizes($sizes)
	{
		EventAlbumSize::model()->deleteAll('album_id=:album_id', array(
			':album_id' => $this->id,
		));
		
		foreach ($sizes as $size) {
			$sizeEntry = PhotoSize::model()->findByAttributes(array(
				'id'	=> (int) $size,
				'state' => PhotoSize::STATE_ACTIVE,
			));
			
			if ($sizeEntry) {
				$s = new EventAlbumSize();
				$s->setAttributes(array(
					'size_id'  => $sizeEntry->id,
					'album_id' => $this->id,					
				));
				$s->save();
				unset($s);
			}
		}
		
		return $this;
	}
	
	/**
	 * Change cover for album
	 * 
	 * @param integer $not
	 * @return EventAlbum 
	 */
	public function changeCover($not = 0)
	{
		$photo = EventPhoto::model()->find(array(
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
        
	/**
	 * Path where original images stores
	 * 
	 * @return string 
	 */
	public function originPath()
	{
		$directory =  $this->_originUploadPath . DIRECTORY_SEPARATOR . $this->encryptedId();
		
		if (!is_dir($directory)) {
			mkdir($directory, 0777, true);
		}
                
        return $directory;
	}
	
	/**
	 * Create directory for album
	 * 
	 * @return EventAlbum 
	 */
	protected function _createDir()
	{
		if (!is_dir($this->_uploadPath)) {
			mkdir($this->_uploadPath, 0777);
		}
		
		return $this;
	}
}