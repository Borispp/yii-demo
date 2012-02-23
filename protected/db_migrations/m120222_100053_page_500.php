<?php

class m120222_100053_page_500 extends CDbMigration
{
	public function up()
	{
		$this->execute("INSERT INTO `page` (`parent_id`,`slug`,`title`,`short`,`content`,`rank`,`type`,`created`,`updated`,`state`) VALUES (0,'page-500','Page 500','Something went wrong, we already know about this','',0,'system','2012-02-21 21:37:10','2012-02-21 21:37:31',1);");
	}

	public function down()
	{
		$this->execute("DELETE FROM `page` WHERE `slug`='page-500'");
	}
}