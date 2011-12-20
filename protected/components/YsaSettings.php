<?php
/**
 * Options wrapper for application
 */
class YsaSettings extends CApplicationComponent
{
    protected $_optionTable;
    
    protected $_optionGroupTable;
    
    protected $_options = array();
    
    protected $_groups = array();
    
    public function init()
    {
        parent::init();
        
        $this->_optionTable = Option::model()->tableName();
        
        $this->_optionGroupTable = OptionGroup::model()->tableName();
    }
    
    public function get($name, $default = '')
    {
        if (isset($this->_options[$name])) {
            return $this->_options[$name];
        }
        
        $this->_loadOption($name, $default);
        
        return isset($this->_options[$name]) ? $this->_options[$name] : $default;
    }
    
    public function getGroup($groupName)
    {
        if (isset($this->_groups[$groupName])) {
            return $this->_groups[$groupName];
        }
        
        $this->_loadGroup($groupName);
        
        return isset ($this->_groups[$groupName]) ? $this->_groups[$groupName] : null;
    }

    protected function _loadOption($name, $default)
    {
        $q = Yii::app()->db
                  ->createCommand()
                  ->select('*')
                  ->from($this->_optionTable)
                  ->where('name=:name', array(
                      ':name' => $name,
                  ));
        
        $row = $q->queryRow();
        
        if (!$row) {
            return false;
        }
        
        if (YsaHelpers::isSerialized($row['value'])) {
            $row['value'] = unserialize($row['value']);
        }
        
        // cache value
        $this->_options[$row['name']] = $row['value'];
        
        return true;
    }
    
    protected function _loadGroup($groupName)
    {
        $q = Yii::app()->db
                  ->createCommand()
                  ->select('o.*')
                  ->from($this->_optionTable . ' o')
                  ->join($this->_optionGroupTable . ' g', 'o.group_id=g.id')
                  ->where('g.slug=:slug', array(
                      ':slug' => $groupName,
                  ));
        
        $options = array();
        $rows = $q->queryAll();
        
        if (!count($rows)) {
            return false;
        }
        
        foreach ($rows as $row) {
            if (YsaHelpers::isSerialized($row['value'])) {
                $row['value'] = unserialize($row['value']);
            }
            $options[$row['name']] = $row['value'];
        }
        
        
        $this->_groups[$groupName] = $options;
        
        return true;
    }

}