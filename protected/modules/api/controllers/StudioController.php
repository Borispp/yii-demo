<?php
class StudioController extends YsaApiController
{
	/**
	 * Basic action — returns all information about app — color, font-family, background image, logo etc.
	 * Inquiry params: [app_key, device_id]
	 * Response params: [logo, name, font_id, bg_color, use_bg_image, bg_image, headers_color, text_color]
	 * @return void
	 */
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

	/**
	 * Returns studio information — photographer rss feeds, description, video etc.
	 * Inquiry params: [app_key, device_id]
	 * Response params: [links -> [name, link], info -> [article, portrait], video, rss -> [type, rss-link, link]]
	 * @return void
	 */
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

	/**
	 * Returns galleries list
	 * Inquiry params: [app_key, device_id]
	 * Response params: [portfolio -> [id, name, preview, number, checksum]]
	 * @return void
	 */
	public function actionGalleriesList()
	{
	}

	/**
	 * Returns images of selected gallery
	 * Inquiry params: [app_key, device_id, gallery_id]
	 * Response params: [images -> [photo_id, thumbnail, fullsize, title, meta, share-link]]
	 * @return void
	 */
	public function actionGalleryImages()
	{
	}

	/**
	 * Checks if gallery'd been up since last fetch.
	 * Inquiry params: [app_key, device_id, gallery_id, checksum]
	 * Response params: [state, checksum]
	 * @return void
	 */
	public function actionIsGalleryUpdated()
	{
	}

	/**
	 * Send contact message from application user to photographer
	 * Inquiry params: [app_key, device_id, fields -> [name -> value]]
	 * Response params: [state]
	 * @return void
	 */
	public function actionSendMessage()
	{
		$this->_commonValidate();
		$this->_validateVars(array('fields' => array(
			'code'		=> 010,
			'message'	=> 'Fields must be not empty',
			'required'	=> TRUE,
		)));
		$obPhotographer = Application::model()->findByKey($_POST['app_key'])->user;
		$body = '';
		foreach($_POST['fields'] as $name => $value)
			$body .= $name.': '.($value ? $value : '')."\n\r";

		Yii::app()->mailer->From = Yii::app()->settings->get('send_mail_from_email');
		Yii::app()->mailer->FromName = Yii::app()->settings->get('send_mail_from_name');
		Yii::app()->mailer->AddAddress($obPhotographer->email, $obPhotographer->first_name.' '.$obPhotographer->last_name);
		Yii::app()->mailer->Subject = 'Mail from iOS application contact form';
		Yii::app()->mailer->AltBody = $body;
		Yii::app()->mailer->getView('standart', array(
				'body'  => $body,
			));
		$this->_render(array(
			'state' => Yii::app()->mailer->Send()
		));
	}
}