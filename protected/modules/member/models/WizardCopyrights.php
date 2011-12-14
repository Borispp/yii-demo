<?php
class WizardCopyrights extends Wizard 
{
    public $photographer_info;
    
    public $blog_rss;
    
    public $facebook;
    
    public $twitter;
    
    public $copyright;

    public function rules() 
    {
        return array(
            array('copyright, photographer_info, blog_rss, facebook, twitter', 'safe'),
        );
    }

    public function prepare()
    {
        parent::prepare();
        
        $this->prepareImage('photographer_info');
        
        return $this;
    }
}