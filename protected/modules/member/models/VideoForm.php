<?php
class VideoForm extends YsaFormModel 
{
	public $video;
	
	public $type = '';
	
	public $code = '';
	
    public function rules() 
    {
        return array(
			array('video', 'required'),
			array('video', 'url'),
			array('video', 'parse'),
			array('type, code', 'safe'),
        );
    }
	
	public function labels()
	{
		return array(
			'video' => 'Video URL'
		);
	}
	
	
	//http://youtu.be/GHfDnS9ugPo
	//http://www.youtube.com/watch?v=GHfDnS9ugPo&feature=g-logo
	//http://vimeo.com/35396305
	public function parse($param)
	{
		if (substr_count($this->{$param}, 'youtu')) {
			$this->type = Studio::VIDEO_YOUTUBE;
		} elseif (substr_count($this->{$param}, 'vimeo')) {
			$this->type = Studio::VIDEO_VIMEO;
		} else {
			$this->_addVideoError();
			return;
		}
		
		if (Studio::VIDEO_YOUTUBE == $this->type) {
			preg_match('~watch\?v=([^&]+)~si', $this->{$param}, $matches);
			// shortcode was inserted
			if (!count($matches)) {
				preg_match('~.be/([^&]+)~si', $this->{$param}, $matches);
			}
			if (!isset($matches[1])) {
				$this->_addVideoError();
				return;
			}
			$this->code = $matches[1];
		} else {
			preg_match('~\.com/(\d+)~si', $this->{$param}, $matches);
			if (!isset($matches[1])) {
				$this->_addVideoError();
				return;
			}
			$this->code = $matches[1];
		}
	}
	
	protected function _addVideoError()
	{
		$this->addError('video', 'This link doesn\'t belong to YouTube and Vimeo.');
	}
}