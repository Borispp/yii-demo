<?php
class StudioController extends YsaApiController
{
	protected function beforeAction($action)
	{
		$this->_commonValidate();
		return parent::beforeAction($action);
	}
	
	protected function _getUrlFromImage(array $image = NULL)
	{
		return $image['url'];
	}

	protected function _getImageSize(array $image = NULL)
	{
		if (file_exists($image['path']))
			return @filesize($image['path']);
		return 0;
	}

	/**
	 * Basic action — returns all information about app — color, font-family, background image, logo etc.
	 * Inquiry params: [app_key, device_id]
	 * Response params: [logo, studio_bg_use_image, studio_bg, studio_bg_image,generic_bg_use_image,generic_bg,generic_bg_image, splash_bg_use_image, splash_bg,splash_bg_image,first_font,second_font,main_color,second_color,studio_name,copyright]
	 * @return void
	 */
	public function actionStyle()
	{
		$this->_commonValidate();
		$hasStudioBgImage = $this->_getApplication()->option('studio_bg') != 'color';
		$hasGenericBgImage = $this->_getApplication()->option('generic_bg') != 'color';
		$hasSplashBgImage = $this->_getApplication()->option('splash_bg') != 'color';
		$this->_render(array(
				'style'                     => $this->_getApplication()->option('style'),
				'logo_use_image'            => 1,
				'logo_filesize'             => $this->_getImageSize($this->_getApplication()->option('logo')),
				'logo'                      => $this->_getUrlFromImage($this->_getApplication()->option('logo')),

				'studio_bg_image_use'       => $hasStudioBgImage,
				'studio_bg'                 => $hasStudioBgImage ? NULL : YsaHelpers::html2rgb($this->_getApplication()->option('studio_bg_color')),
				'studio_bg_image'           => $hasStudioBgImage ? $this->_getUrlFromImage($this->_getApplication()->option('studio_bg_image')) : NULL,
				'studio_bg_image_filesize'  => $hasStudioBgImage ? $this->_getImageSize($this->_getApplication()->option('studio_bg_image')): 0,

				'generic_bg_image_use'      => $hasGenericBgImage,
				'generic_bg'                => $hasGenericBgImage ? NULL : YsaHelpers::html2rgb($this->_getApplication()->option('generic_bg_color')),
				'generic_bg_image'          => $hasGenericBgImage ? $this->_getUrlFromImage($this->_getApplication()->option('generic_bg_image')) : NULL,
				'generic_bg_image_filesize' => $hasGenericBgImage ? $this->_getImageSize($this->_getApplication()->option('generic_bg_image')): 0,

				'splash_bg_image_use'       => $hasSplashBgImage,
				'splash_bg'                 => $hasSplashBgImage ? NULL : YsaHelpers::html2rgb($this->_getApplication()->option('splash_bg_color')),
				'splash_bg_image'           => $hasSplashBgImage ? $this->_getUrlFromImage($this->_getApplication()->option('splash_bg_image')) : NULL,
				'splash_bg_image_filesize'  => $hasSplashBgImage ? $this->_getImageSize($this->_getApplication()->option('splash_bg_image')): 0,

				'first_font'                => $this->_getApplication()->option('main_font'),
				'second_font'               => $this->_getApplication()->option('second_font'),

				'main_color'                => YsaHelpers::html2rgb($this->_getApplication()->option('main_font_color')),
				'second_color'              => YsaHelpers::html2rgb($this->_getApplication()->option('second_font_color')),

				'studio_name'               => $this->_getApplication()->name,
				'copyright'                 => $this->_getApplication()->option('copyright'),
			));
	}

	/**
	 * Returns studio information — photographer rss feeds, description, video etc.
	 * Inquiry params: [app_key, device_id]
	 * Response params: [feeds -> [type, link], links -> [name, link], persons -> [name, photo, text], specials, splash]
	 * @return void
	 */
	public function actionInfo()
	{
		$this->_commonValidate();
		$obStudio = $this->_getApplication()->user->studio;
		$params = array(
			'splash'   => $obStudio->splash,
			'contact'  => $obStudio->contact(),
			'specials' => $obStudio->specialsUrl(),
			'video'    => $obStudio->video(),
			'feeds'    => array(
				array(
					'type' => 'twitter',
					'link' => $obStudio->twitter_feed,
				),
				array(
					'type' => 'facebook',
					'link' => $obStudio->facebook_feed,
				),
				array(
					'type' => 'blog',
					'link' => $obStudio->blog_feed
				),
			));
		foreach($this->_getApplication()->user->studio->persons() as $obPerson)
		{
			$params['persons'][] = array(
				'name'  => $obPerson->name,
				'photo' => $obPerson->photoUrl(),
				'text'  => $obPerson->description
			);
		}

		foreach($this->_getApplication()->user->studio->customLinks() as $obLink)
		{
			$params['links'][] = array(
				'name' => $obLink->name,
				'url'  => $obLink->url,
				'icon' => str_ireplace('.png','', $obLink->icon)
			);
		}

		foreach($this->_getApplication()->user->studio->bookmarkLinks() as $obLink)
		{
			$params['bookmarks'][] = array(
				'name' => $obLink->name,
				'url'  => $obLink->url,
			);
		}
		$this->_render($params);
	}

	/**
	 * Get Portfolio Event List
	 * Inquiry params: [app_key, device_id]
	 * Response params: events->[name,type,description,date,creation_date,filesize,checksumm]
	 * @return void
	 */
	public function actionGetEventList()
	{
		$result = array();
		foreach($this->_getApplication()->user->portfolio_events as $obEvent)
		{
			$result[] = $this->_getEventInformation($obEvent);
		}
		$this->_render(array(
			'events'	=> $result
		));
	}

	/**
	 * Returns event info.
	 * Inquiry params: [app_key, device_id, event_id]
	 * Response params: [name,type,description,date,creation_date,filesize,checksumm]
	 * @return void
	 */
	public function actionGetEventInfo()
	{
		$this->_validateVars(array(
			'event_id'	=> array(
				'message'	=> Yii::t('api', 'event_no_id'),
				'required'	=> TRUE
			),
		));
		if (!$this->_getEvent()->isPortfolio())
			$this->_renderError(Yii::t('api', 'event_album_is_not_portfolio'));
		if (!$this->_getEvent()->isActive())
			$this->_renderError(Yii::t('api', 'event_is_blocked'));
		$this->_render($this->_getEventInformation($this->_getEvent()));
	}

	/**
	 * Returns event info.
	 * Inquiry params: [device_id, event_id, app_key]
	 * Response params: [name,type,description,date,creation_date,filesize,checksumm,can_order,can_share,sizes]
	 * @return void
	 */
	public function actionGetEventAlbums()
	{
		$params = array();
		foreach($this->_getEvent()->albums as $obEventAlbum)
		{
			if (!$obEventAlbum->isActive())
				continue;
			$params['albums'][] = $this->_getEventAlbumInfo($obEventAlbum);
		}
		$this->_render($params);
	}

	/**
	 * Checks if album'd been updated since last fetch.
	 * Inquiry params: [device_id, event_id, app_key, album_id, checksum]
	 * Response params: [state, checksum]
	 * @return void
	 */
	public function actionIsEventAlbumUpdated()
	{
		$this->_validateVars(array(
				'album_id' => array(
					'message'	=> Yii::t('api', 'event_album_no_id'),
					'required'	=> TRUE,
				),
				'checksum' => array(
					'message'	=> Yii::t('api', 'event_album_no_id'),
					'required'	=> TRUE,
				),
			));
		$this->_render(array(
				'state'			=> !$this->_getEventAlbum(TRUE)->checkHash($_POST['checksum']),
				'checksumm'		=> $this->_getEventAlbum(TRUE)->getChecksum(),
				'filesize'		=> $this->_getEventAlbum(TRUE)->size()
			));
	}

	/**
	 * Return All photos
	 * Inquiry params: [device_id, event_id, app_key, album_id]
	 * Response params: [images -> [photo_id filesize name thumbnail fullsize meta rank comments can_share can_order has_sizes sizes share_link]]
	 * @return void
	 */
	public function actionGetEventAlbumPhotos()
	{
		$this->_validateVars(array(
				'album_id' => array(
					'message'	=> Yii::t('api', 'event_album_no_id'),
					'required'	=> TRUE,
				),
			));
		if (!$this->_getEventAlbum(TRUE)->photos)
			$this->_renderError(Yii::t('api', 'event_album_no_photos'));
		$params = array();
		foreach($this->_getEventAlbum(TRUE)->photos as $obPhoto)
			$params['images'][] = $this->_getPhotoInfo($obPhoto);
		$this->_render($params);
	}

	/**
	 * Checks if album'd been updated since last fetch.
	 * Inquiry params: [device_id, event_id, app_key, album_id, photo_id]
	 * Response params: [photo_id filesize name thumbnail fullsize meta rank comments can_share can_order has_sizes sizes share_link]
	 * @return void
	 */
	public function actionGetPhotoInfo()
	{
		$this->_validateVars(array(
				'album_id' => array(
					'message'	=> Yii::t('api', 'event_album_no_id'),
					'required'	=> TRUE,
				),
				'photo_id' => array(
					'message'	=> Yii::t('api', 'event_album_photo_no_id'),
					'required'	=> TRUE,
				),
			));
		$this->_getEventAlbum(TRUE);
		if (!$this->_getEventPhoto())
			$this->_renderError(Yii::t('api', 'event_album_photo_is_wrong'));
		$this->_render($this->_getPhotoInfo($this->_getEventPhoto()));
	}

	/**
	 * Send contact message from client to photographer
	 * Inquiry params: [app_key, device_id, token, subject, message, name, email]
	 * Response params: [state]
	 * @return void
	 */
	public function actionSendMessage()
	{
		$this->_validateVars(array(
			'subject' => array(
				'message'	=> Yii::t('api', 'common_no_field', array('{field}' => 'subject')),
				'required'	=> TRUE,
			),
			'message' => array(
				'message'	=> Yii::t('api', 'common_no_field', array('{field}' => 'message')),
				'required'	=> TRUE,
			),
			'name' => array(
				'message'	=> Yii::t('api', 'common_no_field', array('{field}' => 'name')),
				'required'	=> TRUE,
			),
			'email' => array(
				'message'	=> Yii::t('api', 'common_no_field', array('{field}' => 'email')),
				'required'	=> TRUE,
			),
		));
		$obPhotographer = $this->_getApplication()->user;
		$obStudioMessage = new StudioMessage();

		//$obStudioMessage->client_id = $this->_obClient->id;
		$obStudioMessage->name = $_POST['name'];
		$obStudioMessage->email = $_POST['email'];
		//$obStudioMessage->phone = $this->_obClient->phone;
		$obStudioMessage->subject = @$_POST['subject'];
		$obStudioMessage->message = @$_POST['message'];
		$obStudioMessage->user_id = $obPhotographer->id;
		$obStudioMessage->device_id = $_POST['device_id'];
		if(!$obStudioMessage->save())
			$this->_renderErrors($obStudioMessage->getErrors());
		$this->_render(array('state' => TRUE));
	}
}