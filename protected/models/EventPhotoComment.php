<?php

/**
 * This is the model class for table "event_album".
 *
 * The followings are the available columns in table 'event_album':
 * @property integer $id
 * @property integer $photo_id
 * @property string $created
 * @property string $comment
 *
 * @property Client $client
 */
class EventPhotoComment extends YsaActiveRecord
{
	const NO_NAME = 'unnamed author';

	/**
	 * Returns the static model of the specified AR class.
	 * @return EventPhoto the static model class
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
		return 'event_photo_comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('photo_id', 'numerical', 'integerOnly'=>true),
			array('photo_id, comment', 'required'),
			array('created', 'safe'),
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
			'photo'			=> array(self::BELONGS_TO, 'EventPhoto', 'photo_id'),
			'client'		=> array(self::MANY_MANY, 'Client', 'event_photo_comment_client(comment_id, client_id)'),
			'member'		=> array(self::MANY_MANY, 'Member', 'event_photo_comment_user(comment_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'photo_id' => 'Photo',
			'created' => 'Created',
			'comment' => 'Comment'
		);
	}

	/**
	 * Calls name() method from related user or client
	 * @return string
	 */
	public function name()
	{
		if ($obClient = $this->client)
		{
			$obClient = $this->client;
			if (is_array($this->client))
				list($obClient) = $this->client;
			return $obClient->name;
		}
		elseif ($this->member)
		{
			$obMember = $this->member;
			if (is_array($this->member))
				list($obMember) = $this->member;
			return $obMember->name();
		}

	}

	/**
	 * @param User $obUser
	 * @return void
	 */
	public function appendToUser(User $obUser)
	{
		$obPhotoCommentUser = new EventPhotoCommentUser();
		$obPhotoCommentUser->comment_id = $this->id;
		$obPhotoCommentUser->user_id = $obUser->id;
		var_dump($obPhotoCommentUser->save());
	}

	/**
	 * @param Client $obClient
	 * @return void
	 */
	public function appendToClient(Client $obClient)
	{
		$obPhotoCommentClient = new EventPhotoCommentClient();
		$obPhotoCommentClient->comment_id = $this->id;
		$obPhotoCommentClient->clien_id = $obClient->id;
		$obPhotoCommentClient->save();
	}
}