<?php
class WizardSubmit extends Wizard 
{
    public $finish;
    
    public function save()
    {
        if (Application::STATE_CREATED == $this->_application->state) {
            $this->_application->state = Application::STATE_FILLED;
            $this->_application->save();
        }
    }


    public function rules() 
    {
        return array(
            array('finish', 'safe'),
        );
    }
}