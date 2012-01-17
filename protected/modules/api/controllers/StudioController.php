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
			'order_link'	=> $obStudio->order_link,
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
			$params['persons'][] = array(
				'name'		=> $obPerson->name,
				'photo'		=> $obPerson->photoUrl(),
				'text'		=> $obPerson->description
			);
		foreach($this->_getApplication()->user->studio->links() as $obLink)
			$params['links'][] = array(
				'name'		=> $obLink->name,
				'url'		=> $obLink->url,
			);
		$this->_render($params);
	}

	/**
	 * Returns galleries list
	 * Inquiry params: [app_key, device_id]
	 * Response params: [portfolio -> [gallery_id, name, description, number_of_photos, filesize, checksum]]
	 * @return void
	 */
	public function actionGalleriesList()
	{
		$this->_commonValidate();
		$obStudio = $this->_getApplication()->user->studio;
		$params = array();
		foreach($obStudio->user->portfolio_events as $event)
		{
			foreach($event->albums as $obPortfolioAlbum)
			{
				$params['portfolio'][] = array(
					'gallery_id'		=> $obPortfolioAlbum->id,
					'name'				=> $obPortfolioAlbum->name,
					'description'		=> $obPortfolioAlbum->description,
					'number_of_photos'	=> count($obPortfolioAlbum->photos),
					'filesize'			=> $obPortfolioAlbum->size(),
					'checksum'			=> $obPortfolioAlbum->getChecksum()
				);
			}
		}
		if (!count($params))
			return $this->_renderError('No albums found');
		$this->_render($params);
	}

	/**
	 * Returns images of selected gallery
	 * Inquiry params: [app_key, device_id, gallery_id]
	 * Response params: [images -> [photo_id, thumbnail, fullsize, name, meta, share-link]]
	 * @return void
	 */
	public function actionGalleryImages()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
				'gallery_id' => array(
					'message'	=> 'Gallery id must not be empty',
					'required'	=> TRUE,
				),
			));
		$obPortfolioAlbum = EventAlbum::model()->findByPk($_POST['gallery_id']);
		$this->_checkPhotoAlbum($obPortfolioAlbum);
		if (!count($photos = $obPortfolioAlbum->photos))
			$this->_renderError('Portfolio Album is empty');
		$params = array();
		foreach($photos as $obPortfolioPhoto)
		{
			$params['images'][] = array(
				'photo_id'		=> $obPortfolioPhoto->id,
				'filesize'		=> $obPortfolioPhoto->size,
				'name'			=> $obPortfolioPhoto->name,
				'thumbnail'		=> $obPortfolioPhoto->previewUrl(),
				'fullsize'		=> $obPortfolioPhoto->fullUrl(),
				'meta'			=> $obPortfolioPhoto->exif(),
				//'share-link'	=> ,
			);
		}
		$this->_render($params);
	}

	protected function _checkPhotoAlbum($obPortfolioAlbum = NULL)
	{
		if ($obPortfolioAlbum->event->user->id != $this->_getApplication()->user_id)
			return $this->_renderError('Access denied');
		if (!$obPortfolioAlbum)
			return $this->_renderError('No album found');
		if (!$obPortfolioAlbum->isActive())
			return $this->_renderError('Album is blocked');
	}

	/**
	 * Checks if gallery'd been up since last fetch.
	 * Inquiry params: [app_key, device_id, gallery_id, checksum]
	 * Response params: [state, checksum]
	 * @return void
	 */
	public function actionIsGalleryUpdated()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
				'gallery_id' => array(
					'message'	=> 'Gallery id must not be empty',
					'required'	=> TRUE,
				),
				'checksum' => array(
					'message'	=> 'Checksum id must not be empty',
					'required'	=> TRUE,
				),
			));
		$obPortfolioAlbum = EventAlbum::model()->findByPk($_POST['gallery_id']);
		$this->_checkPhotoAlbum($obPortfolioAlbum);
		$this->_render(array(
				'state'			=> !$obPortfolioAlbum->checkHash($_POST['checksum']),
				'checksumm'		=> $obPortfolioAlbum->getChecksum(),
			));
	}
}