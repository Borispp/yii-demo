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
		$this->_commonValidate();
		$this->_render(array(
				'links'	=> array(
					array(
						'name'	=> 'Portfolio Site',
						'link'	=> 'http://photographer.com/',
					),
					array(
						'name'	=> 'Blog',
						'link'	=> 'http://photographer.com/blog',
					),
					array(
						'name'	=> 'Workshops',
						'link'	=> 'http://photographer.com/workshops',
					),
				),
				'info'	=> array(
					'article'	=> 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.',
					'portrait'	=> 'http://www.dukemagazine.duke.edu/dukemag/issues/010203/images/tw_JAFE_3.jpg',
				),
				'video'	=> '<iframe src="http://player.vimeo.com/video/19705053?byline=0&amp;portrait=0&amp;color=ffffff" width="400" height="180" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
				'rss'	=> array(
					array(
						'type'		=> 'twitter',
						'rss-link'	=> 'http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=flosites',
						'link'		=> 'https://twitter.com/#!/flosites'
					),
					array(
						'type'		=> 'facebook',
						'rss-link'	=> 'http://www.facebook.com/feeds/page.php?format=atom10&id=40796308305',
						'link'		=> 'http://www.facebook.com/flosites'
					),
				),
			));
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