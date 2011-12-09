<?php

/**
 * This is the model class for table "application".
 *
 * The followings are the available columns in table 'application':
 * @property string $id
 * @property integer $user_id
 * @property string $appkey
 * @property string $passwd
 * @property integer $state
 * @property string $name
 * @property string $info
 */
class Application extends YsaActiveRecord
{
    /**
     * Created by member
     */
    const STATE_CREATED = 1;
    
    /**
     * Filled with information
     */
    const STATE_FILLED = 2;
    
    /**
     * Approved by website moderator
     */
    const STATE_APPROVED = 3;
    
    /**
     * Waiting AppStore Approval
     */
    const STATE_WAITING_APPROVAL = 4;
    
    /**
     * Application is ready to work
     */
    const STATE_READY = 5;
    
    /**
     * Unapproved by website moderator
     */
    const STATE_UNAPROVVED = -3;
    
    /**
     * Rejected by AppStore
     */
    const STATE_REJECTED = -5;
    
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'application';
    }

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, appkey, passwd, name', 'required'),
            array('user_id, appkey, name', 'unique'),
            array('user_id, state', 'numerical', 'integerOnly'=>true),
            array('appkey, passwd, name', 'length', 'max'=>100),
            array('info', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, user_id, state, name', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'appkey' => 'Appkey',
            'passwd' => 'Password',
            'state' => 'State',
            'name' => 'Name',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('state',$this->state);
        $criteria->compare('name',$this->name,true);

        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
        ));
    }
    
    public function findByMember($memberId)
    {
        return $this->findByAttributes(array(
            'user_id'   => $memberId,
        ));
    }
    
    public function settings()
    {
        VarDumper::dump('app settings');
    }
    
    public function generatePasswd()
    {
        $this->passwd = YsaHelpers::genRandomString();
    }
    
    public function generateAppKey()
    {
        $this->appkey = YsaHelpers::encrypt(microtime() . YsaHelpers::genRandomString(20) . Yii::app()->params['salt']);
    }
    
    protected function beforeValidate()
    {
	if($this->isNewRecord) {
	    $this->created = $this->updated = new CDbExpression('NOW()');
	}
        
	return parent::beforeValidate();
    }
}