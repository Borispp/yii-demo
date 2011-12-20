<?php

/**
 * This is the model class for table "event_album".
 *
 * The followings are the available columns in table 'event_album':
 * @property integer $id
 * @property integer $photo_id
 * @property integer $user_id
 * @property string $name
 * @property string $created
 * @property string $comment
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
			array('user_id, photo_id', 'numerical', 'integerOnly'=>true),
			array('photo_id, comment', 'required'),
			array('name', 'length', 'max'=>100),
			array('created, user_id, name', 'safe'),
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
            'photo'  => array(self::BELONGS_TO, 'EventPhoto', 'photo_id'),
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
			'photo_id' => 'Photo',
			'name' => 'Name',
			'created' => 'Created',
			'comment' => 'Comment'
		);
    }
	
	public function name()
	{
		if ($this->name) {
			return $this->name;
		} elseif ($this->user_id) {
			$member = Member::model()->findByPk($this->user_id);
			if ($member) {
				return $member->name();
			} else {
				return self::NO_NAME;
			}
		} else {
			return self::NO_NAME;
		}
	}
}