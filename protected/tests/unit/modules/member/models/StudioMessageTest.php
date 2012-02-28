<?php

Yii::import('application.modules.member.models.*');

class SudioMessageTest extends CDbTestCase
{
	public $fixtures = array(
        'users' => 'Member',
		'messages' => 'StudioMessage',
    );
	
	public function testMarkReadAndMarkUnread()
	{
		$this->assertEquals($this->messages('Unread')->unread, 1);
		$this->assertTrue($this->messages('Unread')->markAsRead());
		$this->assertEquals($this->messages('Unread')->unread, 0);
		$this->assertTrue($this->messages('Unread')->markAsUnread());
		$this->assertEquals($this->messages('Unread')->unread, 1);
		
		$this->messages('Unread')->unread = mt_rand(); // unexpected values fail safe
		$this->assertTrue($this->messages('Unread')->markAsRead());
		$this->assertEquals($this->messages('Unread')->unread, 0);
	}
	
	public function testDoNotCreateNotificationOnMarks()
	{
		$this->assertTrue($this->messages('Unread')->markAsRead());	
		$this->assertEquals(0, count(AnnouncementUser::model()->findAllByAttributes(array('user_id' => $this->messages('Unread')->user_id))));
		
		$this->assertTrue($this->messages('Unread')->markAsUnread());	
		$this->assertEquals(0, count(AnnouncementUser::model()->findAllByAttributes(array('user_id' => $this->messages('Unread')->user_id))));
	}
}