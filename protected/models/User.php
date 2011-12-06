<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $email
 * @property string $role
 * @property string $first_name
 * @property string $last_name
 * @property string $activation_key
 * @property string $password
 * @property integer $state
 * @property string $created
 * @property string $updated
 * @property string $last_login
 * @property string $last_login_ip
 */
class User extends YsaActiveRecord
{
    const ROLE_ADMIN = 'admin';
    const ROLE_MEMBER = 'member';
    
    const STATE_BANNED = -1;
    
    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
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
        return 'user';
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
                    array('email, password', 'length', 'max'=>100),
                    array('email', 'unique'),
                    array('role', 'length', 'max'=>6),
                    array('last_login_ip', 'length', 'max'=>20),
                    array('created, updated, last_login', 'safe'),
                    array('email, state, role, first_name, last_name, password', 'required'),
                    // The following rule is used by search().
                    // Please remove those attributes that should not be searched.
                    array('id, email, role, state', 'safe', 'on'=>'search'),
            );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'option' => array(self::HAS_MANY, 'UserOption', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
            return array(
                'id' => 'ID',
                'email' => 'Email',
                'role' => 'Role',
                'password' => 'Password',
                'state' => 'State',
                'created' => 'Created',
                'updated' => 'Updated',
                'last_login' => 'Last Login',
                'last_login_ip' => 'Last Login Ip',
            );
    }

    public function search()
    {
            // Warning: Please modify the following code to remove attributes that
            // should not be searched.

            $criteria=new CDbCriteria;
            $criteria->compare('id',$this->id,true);
            $criteria->compare('email',$this->email,true);
            $criteria->compare('role',$this->role,true);
            $criteria->compare('state',$this->state);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
    }

    public function beforeSave() {
        parent::beforeSave();
        $this->updated = new CDbExpression('NOW()');
        return true;
    }

    public function generateActivationKey()
    {
        $this->activation_key = YsaHelpers::encrypt(microtime() . YsaHelpers::genRandomString(20));
    }
    
    public function encryptPassword()
    {
        $this->password = YsaHelpers::encrypt($this->password);
    }
    
    public function activate()
    {
        $this->state = self::STATE_ACTIVE;
        $this->save();
    }
    
    public function ban()
    {
        $this->state = self::STATE_BANNED;
        $this->save();
    }
    
    public function name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    public function getStates()
    {
        return array(
            self::STATE_ACTIVE      => 'Active',
            self::STATE_INACTIVE    => 'Inactive',
            self::STATE_BANNED      => 'Banned',
        );
    }
    
    protected function beforeValidate()
    {
	if($this->isNewRecord) {
	    $this->created = $this->updated = new CDbExpression('NOW()');
	}
        
	return parent::beforeValidate();
    }
    
    public function state() 
    {
        if ($this->state == self::STATE_BANNED) {
            return 'Banned';
        } else {
            return parent::state();
        }
    }
    
    public function getActivationLink()
    {
        return Yii::app()->createAbsoluteUrl('/activate/k/' . $this->activation_key);
    }
}