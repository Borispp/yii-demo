<?php

/**
 * This is the model class for table "Announcement".
 *
 * The followings are the available columns in table 'Announcement':
 * @property string $id
 * @property integer $message
 *
 * The followings are the available model relations:
 * @property AnnouncementUser $Announcement_user
 */
class Announcement extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Announcement the static model class
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
		return 'announcement';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'numerical', 'integerOnly'=>true),
			array('message', 'required'),
			array('message', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, message', 'safe', 'on'=>'search'),
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
			'announcement_user' => array(self::HAS_MANY, 'AnnouncementUser', 'announcement_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'message' => 'Message',
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
		$criteria->compare('message',$this->message);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
			));
	}

	/**
	 * Return all Announcements to member
	 * @param Member $obMember
	 * @return AnnouncementUser
	 */
	public function getMemberAnnouncements(User $obMember)
	{
		return $this->with(array(
				'announcement_user'=>array(
					// we don't want to select posts
					'select'=>false,
					// but want to get only users with published posts
					'joinType'=>'INNER JOIN',
					'condition'=>'announcement_user.read IS NULL and announcement_user.user_id=:user_id',
					'params'	=> array(
						':user_id' => $obMember->id
					)
				)
			))->findAll();
	}

	/**
	 * Append Announcement to member
	 * @param $obMember
	 * @return void
	 */
	public function notifyMember(User $obMember)
	{
		$obAnnouncementUser = new AnnouncementUser();
		$obAnnouncementUser->user_id = $obMember->id;
		$obAnnouncementUser->announcement_id = $this->id;
		if ($obAnnouncementUser->validate())
			$obAnnouncementUser->save();
	}

	public function findAllByMember(Member $member, $unreadOnly = FALSE)
	{
		$params = array('user_id' => $member->id);
		if ($unreadOnly)
			$params['read'] = NULL;
		
		return AnnouncementUser::model()->findAllByAttributes($params);
	}

	/**
	 * Set state read = 1 to member-specific userAnnouncement or to all userAnnouncements
	 * @param Member|null $obMember
	 * @return void
	 */
	public function read(Member $obMember = NULL)
	{
		if (is_null($obMember))
		{
			foreach($this->announcement_user as $obAnnouncementUser)
			{
				$obAnnouncementUser->read = date(Announcement::FORMAT_DATETIME);
				$obAnnouncementUser->save();
			}
			return;
		}
		$announcementUser = AnnouncementUser::model()->findByAttributes(array(
				'read'				=> NULL,
				'announcement_id'	=> $this->id,
				'user_id'			=> $obMember->id,
			));
		if ($announcementUser)
		{
			$announcementUser->read = date(Announcement::FORMAT_DATETIME);
			$announcementUser->save();
		}
	}

	public function getRead(Member $member)
	{
		$obAnnouncementUser = AnnouncementUser::model()->findByAttributes(array(
				'announcement_id'	=> $this->id,
				'user_id'			=> $member->id,
			));
		return is_null($obAnnouncementUser->read);
	}
}