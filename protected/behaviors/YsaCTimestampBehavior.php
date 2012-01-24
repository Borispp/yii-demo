<?php
class YsaCTimestampBehavior extends CTimestampBehavior
{
	/**
	* Responds to {@link CModel::onBeforeSave} event.
	* Sets the values of the creation or modified attributes as configured
	*
	* @param CModelEvent $event event parameter
	*/
	public function beforeSave($event) {
		if ($this->getOwner()->getIsNewRecord() && ($this->createAttribute !== null))
		{
			if ($this->_hasCreateAttribute())
				$this->getOwner()->{$this->createAttribute} = $this->getTimestampByAttribute($this->createAttribute);
		}
		if ((!$this->getOwner()->getIsNewRecord() || $this->setUpdateOnCreate) && ($this->updateAttribute !== null)) {
			if ($this->_hasUpdateAttribute())
				$this->getOwner()->{$this->updateAttribute} = $this->getTimestampByAttribute($this->updateAttribute);
		}
	}

	/**
	* Responds to {@link CModel::onAfterSave} event.
	* Get real values for creation or modified attributes after pushing timestamp to DB
	*
	* @param CModelEvent $event event parameter
	*/
	public function afterSave($event)
	{
		if (!$this->_hasCreateAttribute() && !$this->_hasUpdateAttribute())
			return;
		
		$obRecord = $this->getOwner()->findByPk($this->getOwner()->getPrimaryKey());
		
		
		if ($this->_hasCreateAttribute())
			$this->getOwner()->{$this->createAttribute} = $obRecord->{$this->createAttribute};
		if ($this->_hasUpdateAttribute())
			$this->getOwner()->{$this->updateAttribute} = $obRecord->{$this->updateAttribute};
	}

	protected function _hasUpdateAttribute()
	{
		return $this->getOwner()->getTableSchema()->getColumn($this->updateAttribute);
	}
	protected function _hasCreateAttribute()
	{
		return $this->getOwner()->getTableSchema()->getColumn($this->createAttribute);
	}
}