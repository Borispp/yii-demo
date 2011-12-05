<?php
class StudioController extends YsaApiController
{
	public function actionStyle()
	{
		$this->_commonValidate();
		$this->_render(array(
				'logo'			=> 'http://www.google.com/logos/2011/Louis_Daguerre-2011-hp.jpg',
				'name'			=> 'Cool Studio',
				'font_id'		=> 0,
				'bg_color'		=> '#FFF',
				'use_bg_image'	=> 0,
				'bg_image'		=> NULL,
				'headers_color'	=> '#CCC',
				'text_color'	=> '#000',
			));
	}

	public function actionInfo()
	{
//		{id, action, device-id} info
//
//		  1. [array] links
//			1. [string] name
//			2. [string] link
//		  2. [array] info
//			1. [text] article
//			2. [link] portrait
//		  3. [string] video embed code
//		  4. [array] rss
//			1. [string] type (blog, twitter, Facebook)
//			2. [string] rss-link
//			3. [string] profile-link

	}

	public function actionGalleriesList()
	{
//		[array] portfolio
//
//		  1. [string] name
//		  2. [integer] gallery-id
//		  3. [link] preview
//		  4. [integer] number of photos
//		  5. [string] checksum

	}

	public function actionGalleryImages()
	{
//		{id, action, gallery_id, device-id}  galleryimages
//
//		  1. [array] images
//			1. [integer] photo id
//			2. [link] thumbnail
//			3. [link] fullsize
//			4. [string] title
//			5. [string] meta
//
//			6. [string] share-link

	}

	public function actionIsGalleryUpdated()
	{
//		{id, action, hash, gallery_id, device-id}  isgalleryupdated
//
//		  1. [integer] state
//		  2. [string] checksum

	}

	public function actionSendMessage()
	{
//
//		1. {id, action, fields:name:value, device-id} sendmessage
//		  1. [integer] state
//
	}
}