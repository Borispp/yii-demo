<?php

/**
 * This is the model class for table "portfolio_photo".
 *
 * The followings are the available columns in table 'portfolio_photo':
 * @property string $id
 * @property integer $album_id
 * @property string $name
 * @property string $basename
 * @property string $extention
 * @property string $meta_type
 * @property string $exif_data
 * @property string $alt
 * @property integer $state
 * @property integer $rank
 * @property string $created
 * @property string $updated
 * @property string $size
 */
class PortfolioPhoto extends YsaPhotoActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PortfolioPhoto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'portfolio_photo';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'album'	 => array(self::BELONGS_TO, 'PortfolioAlbum', 'album_id'),
		);
	}
	
	/**
	 *
	 * @return Album
	 */
    public function album()
    {
        if (null === $this->_album) {
            $this->_album = PortfolioAlbum::model()->findByPk($this->album_id);
        }

        return $this->_album;
    }
	
	public function isOwner()
	{
		return $this->album()->isOwner();
	}

	public function getChecksum()
	{
		return md5($this->basename);
	}
}