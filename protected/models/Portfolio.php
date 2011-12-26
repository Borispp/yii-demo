<?php

/**
 * This is the model class for table "portfolio".
 *
 * The followings are the available columns in table 'portfolio':
 * @property string $id
 * @property integer $studio_id
 * @property string $name
 */
class Portfolio extends YsaActiveRecord
{
	protected $_albums;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Portfolio the static model class
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
		return 'portfolio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('studio_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
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
			'studio' => array(self::BELONGS_TO, 'Studio', 'studio_id'),
			'cat'	 => array(self::HAS_MANY, 'PortfolioCategory', 'portfolio_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'studio_id' => 'Studio',
			'name' => 'name',
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
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
			));
	}

	/**
	 * @param bool $showActiveOnly
	 * @return array
	 */
	public function albums($showActiveOnly = FALSE)
	{
		if (null === $this->_albums) {
			$this->_albums = PortfolioAlbum::model()->findAll(array(
					'condition' => 'portfolio_id=:portfolio_id'.($showActiveOnly ? ' AND state=:state' : ''),
					'params'    => array(
						':portfolio_id' => $this->id,
						':state'		=> self::STATE_ACTIVE
					),
					'order' => 'rank ASC',
				));
		}
		return $this->_albums;
	}

	public function isOwner()
	{
		return $this->studio()->isOwner();
	}

	public function getAlbumById($albumId)
	{
		list($obAlbum) = PortfolioAlbum::model()->findAll(array(
				'condition' => 'portfolio_id=:portfolio_id AND id=:album_id',
				'params'    => array(
					':portfolio_id'	=> $this->id,
					':album_id'		=> $albumId,
				),
				'order' => 'rank ASC',
			));
		return $obAlbum;
	}
}