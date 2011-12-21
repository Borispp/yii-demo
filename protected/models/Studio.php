<?php

/**
 * This is the model class for table "user_option".
 *
 * The followings are the available columns in table 'user_option':
 * @property string $id
 * @property integer $user_id
 * @property string $name
 * @property string $blog_feed
 * @property string $twitter_feed
 * @property string $facebook_feed
 * @property string $created
 * @property string $updated
 */
class Studio extends YsaActiveRecord
{
	protected $_links;
	
	protected $_portfolio;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'studio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('name, facebook_feed, twitter_feed, blog_feed', 'length', 'max'=>100),
			array('facebook_feed, twitter_feed, blog_feed', 'url'),
			array('name, facebook_feed, twitter_feed, blog_feed, created, updated', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'name' => 'Name',
			'created' => 'Created',
			'updated' => 'Updated',
		);
	}
	
	public function links()
	{
		if (null === $this->_links) {
			$this->_links = StudioLink::model()->findAll(array(
				'condition' => 'studio_id=:studio_id',
				'params'	=> array(
					'studio_id' => $this->id,
				),
				'order' => 'rank ASC',
			));
		}
		return $this->_links;
	}
	
	public function portfolio()
	{
		if (null === $this->_portfolio) {
			
			$this->_portfolio = Portfolio::model()->find('studio_id=:studio_id', array('studio_id' => $this->id));
			
			if (!$this->_portfolio) {
				$this->_portfolio = new Portfolio();
				$this->_portfolio->studio_id = $this->id;
				$this->_portfolio->name = 'Portfolio';
				$this->_portfolio->save();
			}
		}
		
		return $this->_portfolio;
	}
}