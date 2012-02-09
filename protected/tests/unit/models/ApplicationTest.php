<?php

class ApplicationTest extends CDbTestCase
{
	public $fixtures=array(
        'users' => 'Member',
    );
	
	public function testUpdateTimeIsEquivalentToCreateTimeOnInsert()
	{
		$app = new Application;
		$app->appkey = mt_rand();
		$app->user_id = $this->users['User_1']['id'];
		$app->save(false);

		$this->assertNotEmpty($app->created);
		$this->assertEquals($app->created, $app->updated);
	}
}
