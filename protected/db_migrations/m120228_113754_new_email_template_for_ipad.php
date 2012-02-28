<?php

class m120228_113754_new_email_template_for_ipad extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->execute("INSERT INTO `email` (`id`, `name`, `subject`, `body`, `alt_body`, `help`)
				VALUES
				(11, 'contact_member', 'You Have a new Contact Message from your iPad application!',
				'<p>From:  %%%name%%%</p>\r\n<p>Email:  %%%email%%%</p>\r\n<p>Date:  %%%date%%%</p>\r\n<p>Phone Number:  %%%phone%%%</p>\r\n<hr/>\r\n<p>Subject:  %%%subject%%%</p>\r\n<p>%%%message%%%</p>\r\n<hr/>\r\n<p>You can view this message in your Inbox  %%%link_to_inbox%%%</p>',
				'From:  %%%name%%%\r\nEmail:  %%%email%%%\r\nDate:  %%%date%%%\r\nPhone Number:  %%%phone%%%\r\n\r\n\r\nSubject:  %%%subject%%%\r\n%%%message%%%\r\n\r\n\r\nYou can view this message in your Inbox  %%%link_to_inbox%%%',
				'When somebody sends a contact message from iPad, member receives this email.\r\nVariables: name, email, phone, subject , message, link_to_inbox, date');
			");
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
		echo "m120228_113754_new_email_template_for_ipad does not support migration down.\n";
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