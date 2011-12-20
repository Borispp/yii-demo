<?php

/**
 * This is the model class for table "option_image".
 *
 * The followings are the available columns in table 'option_image':
 * @property string $id
 * @property string $elm
 * @property integer $elm_id
 * @property string $name
 * @property string $mime
 * @property string $info
 */
class OptionImage extends YsaActiveRecord
{
    protected $_uploadPath;
    
    protected $_uploadUrl;

    public function init() {
        parent::init();
        
        $this->_uploadPath = rtrim(Yii::getPathOfAlias('webroot.images.options'), '/');
        $this->_uploadUrl = Yii::app()->getBaseUrl(true) . '/images/options';
    }
    
    /**
     * Returns the static model of the specified AR class.
     * @return OptionImage the static model class
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
        return 'option_image';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('elm_id', 'numerical', 'integerOnly'=>true),
            array('elm', 'length', 'max'=>20),
            array('name', 'length', 'max'=>50),
            array('info, mime', 'safe'),
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

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'elm' => 'Elm',
            'elm_id' => 'Elm',
            'name' => 'Name',
            'mime' => 'Mime',
            'info' => 'Info',
        );
    }
    
    public function upload($name, $options)
    {   
        $image = new Image($_FILES[$name]['tmp_name']);
        
        if ($options['crop'] == 'yes') {
            $image->crop($options['width'], $options['height']);
        } else {
            $image->resize($options['width'], $options['height']);
        }
        
        $newName = md5($name . Yii::app()->params['salt']) . '.' . $image->__get('ext');
        
        $savePath = $this->_uploadPath . DIRECTORY_SEPARATOR . $newName;
        
        $url = $this->_uploadUrl . '/' . $newName;
        
        $status = $image->save($savePath);
        
        if ($status) {
            $image = new Image($savePath);
            $this->name = $newName;
            $this->mime = $image->__get('mime');
            $this->info = array(
                'file'      => $image->__get('file'),
                'width'     => $image->__get('width'),
                'height'    => $image->__get('height'),
                'type'      => $image->__get('type'),
                'ext'       => $image->__get('ext'),
                'mime'      => $image->__get('mime'),
            );
            $this->info = serialize($this->info);
        }
        
        return $this;
    }
    
    public function findByElement($elmId, $elm = 'option')
    {
        return $this->findByAttributes(array(
            'elm_id'    => $elmId,
            'elm'       => $elm,
        ));
    }
    
    public function url()
    {
        return $this->_uploadUrl . '/' . $this->name;
    }
    
    public function delete()
    {
        $path = $this->_uploadPath . DIRECTORY_SEPARATOR . $this->name;
        if (is_file($path)) {
            unlink($path);
        }
        parent::delete();
    }
}