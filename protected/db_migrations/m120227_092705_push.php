<?php

class m120227_092705_push extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->execute('CREATE TABLE `ipad` (
					  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					  `device_id` varchar(100) DEFAULT NULL,
					  `token` varchar(100) DEFAULT NULL,
					  `created` datetime DEFAULT NULL,
					  `client_id` int(11) unsigned DEFAULT NULL,
					  `app_id` int(11) unsigned NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `client_id` (`client_id`),
					  KEY `app_id` (`app_id`),
					  CONSTRAINT `ipad_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `ipad_ibfk_2` FOREIGN KEY (`app_id`) REFERENCES `application` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;');

			$this->execute('CREATE TABLE `ipad_notification` (
					  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					  `message` varchar(300) DEFAULT NULL,
					  `created` datetime DEFAULT NULL,
					  `application_id` int(11) unsigned DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  KEY `application_id` (`application_id`),
					  CONSTRAINT `ipad_notification_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;');

			$this->execute('CREATE TABLE `ipad_notification_relation` (
					  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					  `ipad_id` int(11) unsigned NOT NULL,
					  `state` tinyint(1) NOT NULL DEFAULT \'0\',
					  `notification_id` int(11) unsigned NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `ipad_id` (`ipad_id`),
					  KEY `notification_id` (`notification_id`),
					  CONSTRAINT `ipad_notification_relation_ibfk_2` FOREIGN KEY (`notification_id`) REFERENCES `ipad_notification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `ipad_notification_relation_ibfk_1` FOREIGN KEY (`ipad_id`) REFERENCES `ipad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;');
			$transaction->commit();
		}
		catch(Exception $e)
		{
			echo "Exception: ".$e->getMessage()."\n";
			$transaction->rollBack();
			return false;
		}
	}

	public function down()
	{
		echo "m120227_092705_push does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}