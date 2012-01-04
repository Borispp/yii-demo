<?php
class YsaAlbumActiveRecord extends YsaActiveRecord
{
    protected $_uploadPath;
    
    protected $_uploadUrl;
	
	protected $_preview;
	
	protected $_previewUrl;
	
	protected $_photos;

	protected $_size;
	
	protected $_hash;
	
	protected $_cover;
	
	protected function _createDir()
	{
		if (!is_dir($this->_uploadPath)) {
			mkdir($this->_uploadPath, 0777);
		}
		
		return $this;
	}
	
    public function attributeLabels()
    {
		return array(
			'id' => 'ID',
			'event_id' => 'Event',
			'portfolio_id' => 'Portfolio',
			'name' => 'Name',
			'description' => 'Description',
			'shooting_date' => 'Shooting Date',
			'place' => 'Place',
			'rank' => 'Rank',
			'state' => 'State',
			'created' => 'Created',
			'updated' => 'Updated',
			'can_order' => 'Available for order',
			'can_share'	=> 'Available for share',
		);
    }
	
	/**
	 * Delete all photos with album
	 * @return bool
	 */
	public function beforeDelete() {
		parent::beforeDelete();

		foreach ($this->photos as $p) {
			$p->delete();
		}
		
		return true;
	}
	
	/**
	 * Set next rank for event album
	 * @return bool
	 */
    public function beforeSave() 
	{
        if($this->isNewRecord) {
            $this->setNextRank();
        }
        return parent::beforeValidate();
    }
	
	public function albumUrl()
	{
		return $this->_uploadUrl . '/' . $this->encryptedId();
	}
	
	public function albumPath()
	{
		$folder = $this->_uploadPath . DIRECTORY_SEPARATOR . $this->encryptedId();
		
		if (!is_dir($folder)) {
			mkdir($folder, 0777);
		}
		
		return $folder;
	}
	
	public function canShare()
	{
		return $this->can_share;
	}
	
	public function canOrder()
	{
		return $this->can_order;
	}
	
	public function preview($htmlOptions = array())
	{
		if (null === $this->_preview) {
			$this->_preview = YsaHtml::image($this->previewUrl(), 'Album Preview', $htmlOptions);
		}
		return $this->_preview;
	}

	/**
	 * @param string $hash
	 * @return bool
	 */
	public function checkHash($hash)
	{
		return $this->getChecksum() == $hash;
	}

	/**
	 * Calculates size of all album photos
	 * @return integer
	 */
	public function size()
	{
		if (!$this->_size)
		{
			foreach ($this->photos as $obPortfolioPhoto)
				$this->_size += $obPortfolioPhoto->size;
		}
		return $this->_size;
	}

	/**
	 * Calculates hash with use of each photo hash
	 * @return string
	 */
	public function getChecksum()
	{
		if (!$this->_hash)
		{
			$hash = array();
			foreach ($this->photos as $obPortfolioPhoto)
				$hash[] = $obPortfolioPhoto->getChecksum();
			sort($hash, SORT_STRING);
			$this->_hash = md5(implode('', $hash));
		}
		return $this->_hash;
	}
}