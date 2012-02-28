<?php

class m120228_140253_unique_field_for_ipad extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->execute("ALTER TABLE `ipad` ADD UNIQUE INDEX (`token`);");
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
		echo "m120228_140253_unique_field_for_ipad does not support migration down.\n";
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