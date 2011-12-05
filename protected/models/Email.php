<?php

/**
 * This is the model class for table "email".
 *
 * The followings are the available columns in table 'email':
 * @property string $id
 * @property string $name
 * @property string $subject
 * @property string $body
 * @property string $alt_body
 * @property string $help
 */
class Email extends YsaActiveRecord
{
    
    protected $_aliasPre  = '%%%';
    protected $_aliasPost = '%%%';
    
    protected $_to;
    
    /**
     * Returns the static model of the specified AR class.
     * @return Email the static model class
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
        return 'email';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name', 'length', 'max'=>50),
            array('subject', 'length', 'max'=>255),
            array('body, alt_body, help', 'safe'),
            array('name', 'unique'),
            array('name, subject, body', 'required'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
            return array(
                    'id' => 'ID',
                    'name' => 'Name',
                    'subject' => 'Subject',
                    'body' => 'Body',
                    'alt_body' => 'Alt Body',
                    'help' => 'Help',
            );
    }

    public function help()
    {
        return nl2br($this->help);
    }
    
    public function findByName($name)
    {
        return $this->model()->findByAttributes(array('name' => $name));
    }
    
    public function send($to, $name, $aliases = array())
    {
        $mail = $this->model()->findByName($name);

        $mail->setTo($to);
        
        if (!$mail) {
            return false;
        }
        
        return $mail->prepare($aliases)
                    ->sendEmail();
    }
    
    public function prepare($aliases = array())
    {
    	foreach ($aliases as $key => $value) {
            $this->subject  = $this->_replaceAlias($key, $value, $this->subject);
            $this->body     = $this->_replaceAlias($key, $value, $this->body);
            $this->alt_body = $this->_replaceAlias($key, $value, $this->alt_body);
    	}
        
        return $this;
    }
    
    public function sendEmail()
    {
        if (is_array($this->_to)) {
            $toEmail = $this->_to[0];
            $toName = $this->_to[1];
        } else {
            $toEmail = $this->_to;
            $toName = null;
        }
        
        $message = new YiiMailMessage();
        
        $message->setSubject($this->subject);
        $message->setBody($this->body);
        $message->setTo($this->_to);
//        $message->setFrom();  ### TODO ###
        
        $numsent = Yii::app()->mail->send($message);
        
        return $numsent;
    }
    
    
    protected function _replaceAlias($key, $value, $string)
    {
        return str_replace($this->_aliasPre . $key . $this->_aliasPost, $value, $string);
    }
    
    public function setTo($to)
    {
        $this->_to = $to;
    }
}