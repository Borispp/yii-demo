<?php
class WizardCopyrights extends Wizard 
{
//    public $photographer_info;
//    
//    public $blog_rss;
//    
//    public $facebook;
//    
//    public $twitter;
    
    public $copyright;

    public function rules() 
    {
        return array(
            array('copyright', 'safe'),
			//, photographer_info, blog_rss, facebook, twitter
        );
    }

    public function prepare()
    {
        parent::prepare();
        
//        $this->prepareImage('photographer_info');
        
        return $this;
    }
}