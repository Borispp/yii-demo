<?php

/**
 * This is the model class for table "studio_message".
 *
 * The followings are the available columns in table 'studio_message':
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $device_id
 * @property string $phone
 * @property string $subject
 * @property string $message
 * @property integer $user_id
 * @property string $created
 * @property integer $unread
 */
class StudioMessage extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return StudioMessage the static model class
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
		return 'studio_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message, user_id, name, email, subject', 'required'),
			array('user_id, unread', 'numerical', 'integerOnly'=>true),
			array('name, email, subject', 'length', 'max'=>200),
			array('device_id, phone', 'length', 'max'=>50),
			array('created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, email, device_id, phone, subject, message, user_id, created, unread', 'safe', 'on'=>'search'),
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
		);
	}

	public function searchOptions()
	{
		if (null !== Yii::app()->session[self::SEARCH_SESSION_NAME]) {
			$values = Yii::app()->session[self::SEARCH_SESSION_NAME];
		}
        $options = array(
            'keyword' => array(
                'label'     => 'Keyword',
                'type'      => 'text',
                'default'   => '',
                'value'     => isset($values['keyword']) ? $values['keyword'] : '',
            ),
            'order_by' => array(
                'label' => 'Order By',
                'type'  => 'select',
                'options' => array(
                    'id'    => 'ID',
                    'email'  => 'Email',
                    'created'  => 'Date',
                ),
				'value'     => isset($values['order_by']) ? $values['order_by'] : '',
            ),
            'order_sort' => array(
                'label' => 'Order Sort',
                'type'  => 'select',
                'options' => array(
                    'ASC'  => 'ASC',
                    'DESC' => 'DESC',
                ),
				'value'     => isset($values['order_sort']) ? $values['order_sort'] : '',
            ),
            'unread' => array(
                'label'             => 'State',
                'type'              => 'select',
                'addEmptyOption'    => true,
                'options'           => array(1 => 'Unread', 0=> 'Read'),
				'value'     => isset($values['unread']) ? $values['unread'] : '',
            ),
        );

		return $options;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'email' => 'Email',
			'device_id' => 'Device',
			'phone' => 'Phone',
			'subject' => 'Subject',
			'message' => 'Message',
			'user_id' => 'User',
			'created' => 'Created',
			'unread' => 'Unread',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('device_id',$this->device_id,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('unread',$this->unread);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function searchCriteria()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('user_id', Member::model()->findByPk(Yii::app()->user->getId())->id);

		$fields = Yii::app()->session[self::SEARCH_SESSION_NAME];

		if (null === $fields) {
			return $criteria;
		}

		extract($fields);

		// search by keyword
		if (isset($keyword) && $keyword) {
			$criteria->compare('message', $keyword, true, 'AND');
		}

		// search by state
		if (isset($unread) && $unread != '') {
			$criteria->compare('unread', $unread);
		}

		// sort entries
		if (isset($order_by) && isset($order_sort)) {
			if (!in_array($fields['order_by'], array_keys($this->attributes))) {
				$order_by = 'id';
			}

			if (!in_array($order_sort, array('ASC', 'DESC'))) {
				$order_sort = 'DESC';
			}

			$criteria->order = $order_by . ' ' . $order_sort;

		}

		return $criteria;
	}
}