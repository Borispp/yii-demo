<?php
abstract class Wizard extends YsaFormModel 
{
    const BG_IMAGE = 'image';
    
    const BG_COLOR = 'color';

    protected $_class;
    
    protected $_application;
    
    public function init() 
    {
        parent::init();
        
        $this->_class = get_class($this);
    }
    
    public function setApplication($app)
    {
        $this->_application = $app;
        
        return $this;
    }
    
    /**
     * Load default values from database
     */
    public function loadDefaultValues()
    {
        foreach ($this->saveFields() as $value) {
            $this->$value = $this->_application->option($value);
        }
        
        return $this;
    }
    
    /**
     * Prepare data for saving
     */
    public function prepare()
    {
        $this->attributes = $_POST[$this->_class];
        
        return $this;
    }
    
    /**
     * Set save fields for model
     * return array
     */
    public function saveFields()
    {
        return array_keys($this->attributes);
    }

    /**
     * Save options on current wizard step
     */
    public function save()
    {
        foreach ($this->saveFields() as $value) {
            if ($this->$value) {
                $this->_application->editOption($value, $this->$value);
            }
        }
        
        return $this;
    }
    
    public function prepareImage($name)
    {
        $this->$name = CUploadedFile::getInstance($this, $name);
        
        return $this;
    }
    
    public function prepareBackgroundType($name)
    {
        $this->$name = $_POST[$this->_class][$name];
        if (!in_array($this->$name, array(self::BG_COLOR, self::BG_IMAGE))) {
            $this->$name = self::BG_IMAGE;
        }
        
        return $this;
    }
    
    public function prepareFont($name)
    {
        $fontList = array_keys($this->getFontList());
        $this->$name = $_POST[$this->_class][$name];
        if (!in_array($this->$name, $fontList)) {
            $this->$name = $fontList[0];
        }
        
        return $this;
    }
    
    public function getFontList()
    {
        return array(
            'arial'     => 'Arial',
            'helvetica' => 'Helvetica',
            'georgia'   => 'Georgia',
        );
    }
}