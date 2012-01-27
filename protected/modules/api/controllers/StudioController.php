<?php
class StudioController extends YsaApiController
{
	protected function _getUrlFromImage(array $image = NULL)
	{
		return $image['url'];
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
		$this->_render(array(
				'logo'					=> $this->_getUrlFromImage($this->_getApplication()->option('logo')),

				'studio_bg_use_image'	=> (bool)$this->_getApplication()->option('studio_bg'),
				'studio_bg'				=> $this->_getApplication()->option('studio_bg') ? NULL : YsaHelpers::html2rgb($this->_getApplication()->option('studio_bg_color')),
				'studio_bg_image'		=> $this->_getApplication()->option('studio_bg') ? $this->_getUrlFromImage($this->_getApplication()->option('studio_bg_image')) : NULL,

				'generic_bg_use_image'	=> (bool)$this->_getApplication()->option('generic_bg'),
				'generic_bg'			=> $this->_getApplication()->option('generic_bg') ? NULL : YsaHelpers::html2rgb($this->_getApplication()->option('generic_bg_color')),
				'generic_bg_image'		=> $this->_getApplication()->option('generic_bg') ? $this->_getUrlFromImage($this->_getApplication()->option('generic_bg_image')) : NULL,

				'splash_bg_use_image'	=> (bool)$this->_getApplication()->option('splash_bg'),
				'splash_bg'				=> $this->_getApplication()->option('splash_bg') ? NULL : YsaHelpers::html2rgb($this->_getApplication()->option('splash_bg_color')),
				'splash_bg_image'		=> $this->_getApplication()->option('splash_bg') ? $this->_getUrlFromImage($this->_getApplication()->option('splash_bg_image')) : NULL,

				'first_font'			=> $this->_getApplication()->option('main_font'),
				'second_font'			=> $this->_getApplication()->option('second_font'),

				'main_color'			=> YsaHelpers::html2rgb($this->_getApplication()->option('main_font_color')),
				'second_color'			=> YsaHelpers::html2rgb($this->_getApplication()->option('second_font_color')),

				'studio_name'			=> $this->_getApplication()->name,
				'copyright'				=> $this->_getApplication()->option('copyright'),
				'contact'				=> $this->_getApplication()->user->studio->contact()
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
			'splash'		=> $obStudio->splash,
			'specials'		=> $obStudio->specialsUrl(),
			'video'			=> $obStudio->video(),
			'feeds'	=> array(
				array(
					'type'		=> 'twitter',
					'link'	=> $obStudio->twitter_feed,
				),
				array(
					'type'		=> 'facebook',
					'link'	=> $obStudio->facebook_feed,
				),
				array(
					'type'		=> 'blog',
					'link'	=> $obStudio->blog_feed
				),
			));
		foreach($this->_getApplication()->user->studio->persons() as $obPerson)
		{
			$params['persons'][] = array(
				'name'		=> $obPerson->name,
				'photo'		=> $obPerson->photoUrl(),
				'text'		=> $obPerson->description
			);
		}

		foreach($this->_getApplication()->user->studio->customLinks() as $obLink)
		{
			$params['links'][] = array(
				'name'		=> $obLink->name,
				'url'		=> $obLink->url,
				'icon'		=> $obLink->icon
			);
		}

		foreach($this->_getApplication()->user->studio->bookmarkLinks() as $obLink)
		{
			$params['bookmarks'][] = array(
				'name'		=> $obLink->name,
				'url'		=> $obLink->url,
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
				'message'	=> 'No event ID found',
				'required'	=> TRUE
			),
		));
		if (!$this->_getEvent()->isPortfolio())
			$this->_renderError('Requested event should be portfolio');
		if (!$this->_getEvent()->isActive())
			$this->_renderError('Requested event is blocked');
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
			$sizes = array();
			if ($obEventAlbum->canOrder() && $obEventAlbum->size())
			{
				foreach($obEventAlbum->sizes as $obSize)
					$sizes[$obSize->title] = array(
						'height'	=> $obSize->height,
						'width'		=> $obSize->width,
					);
			}
			$params['albums'][] = array(
				'name'				=> $obEventAlbum->name,
				'date'				=> $obEventAlbum->shooting_date,
				'place'				=> $obEventAlbum->place,
				'album_id'			=> $obEventAlbum->id,
				'preview'			=> $obEventAlbum->previewUrl(),
				'number_of_photos'	=> count($obEventAlbum->photos),
				'filesize'			=> $obEventAlbum->size(),
				'checksum'			=> $obEventAlbum->getChecksum(),
				'can_order'			=> $obEventAlbum->canOrder(),
				'can_share'			=> $obEventAlbum->canShare(),
				'order_link'		=> $obEventAlbum->order_link,
				'sizes'				=> $sizes
			);
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
					'message'	=> 'Album id must not be empty',
					'required'	=> TRUE,
				),
				'checksum' => array(
					'message'	=> 'Checksum id must not be empty',
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
					'message'	=> 'Gallery id must not be empty',
					'required'	=> TRUE,
				),
			));
		if (!$this->_getEventAlbum(TRUE)->photos)
			$this->_renderError('Album has no photos');
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
					'message'	=> 'Album id must not be empty',
					'required'	=> TRUE,
				),
				'photo_id' => array(
					'message'	=> 'Photo id must not be empty',
					'required'	=> TRUE,
				),
			));
		$this->_getEventAlbum(TRUE);
		if (!$this->_getEventPhoto())
			$this->_renderError('Album has no such photo');
		$this->_render($this->_getPhotoInfo($this->_getEventPhoto()));
	}
}