<?php

/**
 * This is the model class for table "client".
 *
 * The followings are the available columns in table 'client':
 * @property string $id
 * @property integer $owner_id
 * @property string $passwd
 * @property string $first_name
 * @property string $last_name
 * @property integer $state
 * @property string $note
 * @property string $created
 * @property string $updated
 * @property string $email
 */
class Client extends YsaActiveRecord
{
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
            array('owner_id, passwd, note, email', 'required'),
            array('passwd, email', 'unique'),
            array('owner_id, state', 'numerical', 'integerOnly'=>true),
            array('passwd, first_name, last_name', 'length', 'max'=>50),
            array('created, updated', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, owner_id, passwd, first_name, last_name, state', 'safe', 'on'=>'search'),
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
            'user'=>array(self::BELONGS_TO, 'User', 'owner_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'owner_id' => 'Owner',
            'passwd' => 'Passwd',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'state' => 'State',
            'note' => 'Note',
            'created' => 'Created',
            'updated' => 'Updated',
            'email' => 'Email',
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
            $criteria->compare('owner_id',$this->owner_id);
            $criteria->compare('passwd',$this->passwd,true);
            $criteria->compare('first_name',$this->first_name,true);
            $criteria->compare('last_name',$this->last_name,true);
            $criteria->compare('state',$this->state);
            $criteria->compare('note',$this->note,true);
            $criteria->compare('created',$this->created,true);
            $criteria->compare('updated',$this->updated,true);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
    }
    
    public function name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function generatePassword($len = 8)
    {
        $hex = md5(Yii::app()->params['salt'] . uniqid("", true));

        $pack = pack('H*', $hex);
        $tmp =  base64_encode($pack);

        $uid = preg_replace("#(*UTF8)[^A-Za-z0-9]#", "", $tmp);

        $len = max(4, min(128, $len));

        while (strlen($uid) < $len)
            $uid .= gen_uuid(22);

        $this->passwd = substr($uid, 0, $len);
    }
    
    public function beforeSave() {
        parent::beforeSave();
        if($this->isNewRecord) {
	    $this->created = new CDbExpression('NOW()');
	}
        $this->updated = new CDbExpression('NOW()');
        return true;
    }
}