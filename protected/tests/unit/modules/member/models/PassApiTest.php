<?php

Yii::import('application.modules.member.models.*');

class PassApiTest extends CDbTestCase
{
	public $fixtures = array(
        'users' => 'Member',
		'options' => 'UserOption'
    );
	
	public function testIsMemberLinked()
	{
		$member = new Member;
		$pass = new PassApi;
		$this->assertFalse($pass->isLinked($member));
		
		$this->assertTrue($pass->isLinked($this->users('User_1')));
	}
	
	public function testUnlink()
	{
		$pass = new PassApi;
		$this->assertTrue($pass->isLinked($this->users('User_1')));
		
		$pass->unlink($this->users('User_1'));
		$this->assertFalse($pass->isLinked($this->users('User_1')));
	}
	
	public function testChangeImageSizeInUrl()
	{
		$new_url = PassApi::changeImageSizeInUrl('http://some.site/event_key/img_key_'.PassApi::PHOTO_SIZE_MEDIUM.'.jpg', PassApi::PHOTO_SIZE_LARGE);
		$this->assertEquals('http://some.site/event_key/img_key_l.jpg', $new_url);
	}
}
