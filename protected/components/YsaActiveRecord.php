<?php
class YsaActiveRecord extends CActiveRecord
{
    const STATE_ACTIVE = 1;
    const STATE_INACTIVE = 0;
    
    const LEVEL = '—';
    
    public function getStates()
    {
        return array(
            self::STATE_ACTIVE      => 'Active',
            self::STATE_INACTIVE    => 'Inactive',
        );
    }
    
    public function state()
    {
        switch ($this->state) {
            case self::STATE_ACTIVE:
                return 'Active';
                break;
            case self::STATE_INACTIVE:
                return 'Inactive';
                break;
        }
    }
    
    /**
     * Set created | updated values for AR
     * 
     * @return bool
     */
    public function beforeValidate() {
        
        if($this->isNewRecord && $this->hasAttribute('created')) {
	    $this->created = new CDbExpression('NOW()');
	}
        if($this->hasAttribute('updated')) {
            $this->updated = new CDbExpression('NOW()');
        }
        
        return parent::beforeValidate();
    }
}