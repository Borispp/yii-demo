<?php
class YsaOptionActiveRecord extends YsaActiveRecord
{
    const TYPE_TEXT     = 1;
    const TYPE_TEXTAREA = 2;
    const TYPE_DROPDOWN = 3;
    const TYPE_CHECKBOX = 4;
    const TYPE_RADIO    = 5;
    const TYPE_IMAGE    = 6;
    
    protected $_image;
	
    public function type()
    {
        return OptionType::model()->findByPk($this->type_id);
    }
    
    public function getTypes()
    {
        $entries = OptionType::model()->findAll();
        $types = array();
        foreach ($entries as $entry) {
            $types[$entry->id] = $entry->name;
        }
        return $types;
    }
	
	public function findByGroup($id)
    {
        return $this->findAll(array(
            'condition' => 'group_id=:group_id',
            'params'    => array(':group_id' => $id),
        ));
    }
    
    public function renderField()
    {
        $field = null;
        $fieldName = 'id[' . $this->id . ']';
        
        switch ($this->type_id) {
            case self::TYPE_TEXT:
                $field = CHtml::textField($fieldName, $this->value);
                break;
            case self::TYPE_TEXTAREA:
                $field = CHtml::textArea($fieldName, $this->value, array('data-autogrow' => 'true', 'cols' => 80, 'rows' => 3));
                break;
            case self::TYPE_DROPDOWN:
                $field = CHtml::dropDownList($fieldName, $this->value, $this->getOptionOptionsList(true));
                break;
            case self::TYPE_CHECKBOX:
                $field = CHtml::checkBox($fieldName, intval($this->value));
                break;
            case self::TYPE_RADIO:
                $field = CHtml::radioButtonList($fieldName, $this->value, $this->getOptionOptionsList(), array('separator' => ' '));
                break;
            case self::TYPE_IMAGE:
                $field = YsaHtml::optionImage('image' . $this->id, $this->value, array(), array('id' => $this->id));
                break;
        }
        
        return $field;
    }
}