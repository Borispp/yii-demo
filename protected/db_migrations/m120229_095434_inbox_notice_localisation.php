<?php

class m120229_095434_inbox_notice_localisation extends CDbMigration
{
	public function up()
	{
		$source = new TranslationSource();
		$source->setAttributes(array(
			'category'	=> 'notice',
			'message'	=> html_entity_decode('unread_in_inbox'),
		));
		$source->save();
		$translation = new Translation();
		$translation->setAttributes(array(
			'id'		=> $source->id,
			'language'	=> 'en',
			'translation' => 'You have {count} unread inbox message{message_ending}. {link}',
		));
		$translation->save();
	}

	public function down()
	{
		echo "m120229_095434_inbox_notice_localisation does not support migration down.\n";
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