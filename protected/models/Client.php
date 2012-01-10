<?php

/**
 * This is the model class for table "client".
 *
 * The followings are the available columns in table 'client':
 * @property integer $id
 * @property integer $application_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $description
 * @property integer $state
 * @property string $created
 * @property string $updated
 * @property string $added_with
 *
 * The followings are the available model relations:
 * @property Application $application
 */
class Client extends YsaActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Client the static model class
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
		return 'client';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state', 'numerical', 'integerOnly'=>true),
			array('application_id', 'length', 'max'=>11),
			array('name, email, password, phone', 'length', 'max'=>100),
			array('added_with, description, created, updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, application_id, name, email, password, phone, description, state, created, updated', 'safe', 'on'=>'search'),
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
			'application' => array(self::BELONGS_TO, 'Application', 'application_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'application_id' => 'Application',
			'name' => 'Name',
			'email' => 'Email',
			'password' => 'Password',
			'phone' => 'Phone',
			'description' => 'Description',
			'state' => 'State',
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
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('application_id',$this->application_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function searchCriteria()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('application_id', Member::model()->findByPk(Yii::app()->user->getId())->application->id);
		$fields = Yii::app()->session[self::SEARCH_SESSION_NAME];

		if (null === $fields) {
			return $criteria;
		}

		extract($fields);

		// search by state
		if (isset($state) && $state != '')
		{
			$criteria->compare('state', $state);
		}

		if (isset($added_with) && $added_with != '')
		{
			$criteria->compare('added_with', $added_with);
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

		/**
	 * Get search options for search panel
	 *
	 * @return array
	 */
	public function searchOptions()
	{
		if (null !== Yii::app()->session[self::SEARCH_SESSION_NAME]) {
			$values = Yii::app()->session[self::SEARCH_SESSION_NAME];
		}

		$options = array(
			'order_by' => array(
				'label' => 'Order By',
				'type'  => 'select',
				'options' => array(
					'id'    => 'ID',
					'name'  => 'Name',
					'email'  => 'Email',
					'created'  => 'Register Date',
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
			'state' => array(
				'label'             => 'State',
				'type'              => 'select',
				'addEmptyOption'    => true,
				'options'           => Client::model()->getStates(),
				'value'     => isset($values['state']) ? $values['state'] : '',
			),
			'added_with' => array(
				'label'             => 'Added',
				'type'              => 'select',
				'addEmptyOption'    => true,
				'options'           => Client::model()->getAddedWithList(),
				'value'     => isset($values['added_with']) ? $values['added_with'] : '',
			),
		);

		return $options;
	}

	public function isOwner()
	{
		return $this->application->user_id == Yii::app()->user->id;
	}

	public function getAddedWithList()
	{
		return array(
			'member'	=> 'by Photographer',
			'ipad'		=> 'from ipad'
		);
	}

	public function getAddedWith()
	{
		$addedWith = $this->getAddedWithList();
	}

}