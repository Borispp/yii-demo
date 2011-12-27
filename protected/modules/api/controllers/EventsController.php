<?php
class EventsController extends YsaApiController
{
	protected $_obEventAlbum = NULL;
	protected $_obEventPhoto = NULL;
	/**
	 * @return Event
	 */
	protected function _getEvent()
	{
		return Event::model()->findByPk($_POST['event_id']);
	}

	/**
	 * @return EventAlbum
	 */
	protected function _getEventAlbum()
	{
		if (!$this->_obEventAlbum)
		{
			$obEventAlbum = EventAlbum::model()->findByPk($_POST['album_id']);
			if (!$obEventAlbum)
				return $this->_renderError('020', 'Event album not found');
			if ($obEventAlbum->event_id != $_POST['event_id'] || $obEventAlbum->isActive())
				return $this->_renderError('021', 'Access to event album restricted');
			$this->_obEventAlbum = $obEventAlbum;
		}
		return $this->_obEventAlbum;
	}

	/**
	 * @return EventAlbum
	 */
	protected function _getEventPhoto()
	{
		if (!$this->_obEventPhoto)
		{
			$obEventPhoto = EventPhoto::model()->findByPk($_POST['photo_id']);
			if (!$obEventPhoto)
				return $this->_renderError('020', 'Event album not found');
			if ($obEventPhoto->album_id != $_POST['album_id'] || $obEventPhoto->isActive())
				return $this->_renderError('021', 'Access to event album restricted');
			$this->_obEventPhoto = $obEventPhoto;
		}
		return $this->_obEventPhoto;
	}

	/**
	 * Validates vars and checks token match
	 * @return void
	 */
	protected function _validateAuth()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
				'token'		=> array(
					'message'	=> 'No token received',
					'code'		=> '006',
					'required'	=> TRUE,
					'event_id'	=> array(
						'code'		=> '111',
						'message'	=> 'No event ID found',
						'required'	=> TRUE
					)
				)));
		$this->_checkAuth();
	}

	/**
	 * Action client authorization.
	 * Inquiry params: [app_key, device_id, password, event_id]
	 * Response params: [state]
	 * @return void
	 */
	public function actionLogout()
	{
		$this->_commonValidate();
		$obEvents = EventAuth::model()->findByAttributes(array(
				'device_id'	=> $_POST['device_id']
			));
		if (!$obEvents)
			return;
		$obEvents = is_array($obEvents) ? $obEvents : array($obEvents);
		foreach($obEvents as $obEvent)
			$obEvent->delete();
		$this->_render(array('state' => TRUE));
	}

	/**
	 * Action client authorization.
	 * Inquiry params: [app_key, device_id, password, event_id]
	 * Response params: [token, state, message]
	 * @return void
	 */
	public function actionAddEvent()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
				'password'	=> array(
					'code'		=> '005',
					'message'	=> 'No password received',
					'required'	=> TRUE,
					'event_id'	=> array(
						'code'		=> '111',
						'message'	=> 'No event ID found',
						'required'	=> TRUE
					)
				)));
		if (!$token = EventAuth::model()->authByPassword($_POST['password'], $_POST['app_key'], $_POST['event_id'], $_POST['device_id']))
			$this->_render(array(
					'state'		=> 0,
					'message'	=> 'Authorization by password failed',
					'token'		=> NULL,
				));
		$this->_render(array(
				'state'		=> 1,
				'message'	=> '',
				'token'		=> $token
			));
	}

	/**
	 * Action event drop.
	 * Inquiry params: [app_key, device_id, password, event_id]
	 * Response params: [state]
	 * @return void
	 */
	public function actionRemoveEvent()
	{
		$this->_commonValidate();
		$this->_validateAuth();
		EventAuth::model()->removeAuth($_POST['token'], $_POST['app_key'], $_POST['event_id'], $_POST['device_id']);
		$this->_render(array(
				'state'		=> 1,
			));
	}

	/**
	 * Returns event info.
	 * Inquiry params: [app_key, device_id, token, event_id]
	 * Response params: [name,type,description,date,creation_date,filesize,checksumm]
	 * @todo Add checksumm and filesize
	 * @return void
	 */
	public function actionGetEventInfo()
	{
		$this->_commonValidate();
		$this->_validateAuth();
		$obEvent = $this->_getEvent();

		$this->_render(array(
				'name'			=> $obEvent->name,
				'type'			=> $obEvent->type(),
				'description'	=> $obEvent->description,
				'date'			=> $obEvent->date,
				'creation_date'	=> $obEvent->created
			));
	}

	/**
	 * Returns event info.
	 * Inquiry params: [device_id, event_id, token, app_key]
	 * Response params: [name,type,description,date,creation_date,filesize,checksumm,can_order,can_share,sizes]
	 * @return void
	 */
	public function actionGetEventAlbums()
	{
		$this->_commonValidate();
		$this->_validateAuth();
		$obEvent = $this->_getEvent();
		$params = array();
		foreach($obEvent->albums() as $obEventAlbum)
		{
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
				'number_of_photos'	=> count($obEventAlbum->photos()),
				'filesize'			=> $obEventAlbum->size(),
				'checksum'			=> $obEventAlbum->getChecksum(),
				'can_order'			=> $obEventAlbum->canOrder(),
				'can_share'			=> $obEventAlbum->canShare(),
				'sizes'				=> $sizes
			);
		}
		$this->_render($params);
	}

	/**
	 * Checks if album'd been updated since last fetch.
	 * Inquiry params: [device_id, event_id, token, app_key, album_id, checksum]
	 * Response params: [state, checksum, filesize]
	 * @return void
	 */
	public function actionIsEventAlbumUpdated()
	{
		$this->_commonValidate();
		$this->_validateAuth();
		$this->_validateVars(array(
				'album_id' => array(
					'code'		=> '011',
					'message'	=> 'Gallery id must not be empty',
					'required'	=> TRUE,
				),
				'checksum' => array(
					'code'		=> '012',
					'message'	=> 'Checksum id must not be empty',
					'required'	=> TRUE,
				),
			));
		$this->_render(array(
				'state'			=> $this->_getEventAlbum()->checkHash($_POST['checksum']),
				'checksumm'		=> $this->_getEventAlbum()->getChecksum(),
				'filesize'		=> $this->_getEventAlbum()->size()
			));
	}

	/**
	 * Return All photos
	 * Inquiry params: [device_id, event_id, token, app_key, album_id]
	 * Response params: [images -> [photo_id filesize name thumbnail fullsize meta rank comments can_share can_order has_sizes sizes share_link]]
	 * @return void
	 */
	public function actionGetEventAlbumPhotos()
	{
		$this->_commonValidate();
		$this->_validateAuth();
		$this->_validateVars(array(
				'album_id' => array(
					'code'		=> '031',
					'message'	=> 'Gallery id must not be empty',
					'required'	=> TRUE,
				),
			));
		if (!$this->_getEventAlbum()->photo)
			$this->_renderError('041', 'Album has no photos');
		$params = array();
		foreach($this->_getEventAlbum()->photo as $obPhoto)
			$params['images'][] = $this->_getPhotoInfo($obPhoto);
		$this->_render($params);
	}

	/**
	 * Checks if album'd been updated since last fetch.
	 * Inquiry params: [device_id, event_id, token, app_key, album_id, photo_id]
	 * Response params: [photo_id filesize name thumbnail fullsize meta rank comments can_share can_order has_sizes sizes share_link]
	 * @return void
	 */
	public function actionGetPhotoInfo()
	{
		$this->_commonValidate();
		$this->_validateAuth();
		$this->_validateVars(array(
				'album_id' => array(
					'code'		=> '031',
					'message'	=> 'Album id must not be empty',
					'required'	=> TRUE,
				),
				'photo_id' => array(
					'code'		=> '033',
					'message'	=> 'Photo id must not be empty',
					'required'	=> TRUE,
				),
			));
		$this->_getEventAlbum();
		if (!$this->_getEventPhoto());
			$this->_renderError('041', 'Album has no such photo');
		$this->_render($this->_getEventPhoto());
	}

	/**
	 * @param EventPhoto $obPhoto
	 * @return array
	 */
	protected function _getPhotoInfo(EventPhoto $obPhoto)
	{
		$comments = array();
		foreach($obPhoto->comments as $obComment)
		{
			$comments[] = array(
				'comment_id'	=> $obComment->id,
				'name'			=> $obComment->name,
				'date'			=> $obComment->created,
				'comment'		=> $obComment->comment
			);
		}
		$sizes = array();
		$hasSizes = FALSE;
		if ($obPhoto->sizes)
		{
			$hasSizes = TRUE;
			foreach($obPhoto->sizes as  $obSize)
			{
				$sizes[$obSize->title] = array(
					'height'	=> $obSize->height,
					'width'		=> $obSize->width,
				);
			}
		}
		return array(
			'photo_id'		=> $obPhoto->id,
			'filesize'		=> $obPhoto->size,
			'name'			=> $obPhoto->name,
			'thumbnail'		=> $obPhoto->previewUrl(),
			'fullsize'		=> $obPhoto->fullUrl(),
			'meta'			=> $obPhoto->exif(),
			'rank'			=> $obPhoto->rating(),
			'comments'		=> $comments,
			'can_share'		=> $obPhoto->canShare(),
			'can_order'		=> $obPhoto->canOrder(),
			'has_sizes'		=> $hasSizes,
			'sizes'			=> $sizes,
			'share_link'	=> $obPhoto->shareUrl()
		);
	}


	/**
	 * Send rank for event photo
	 * Inquiry params: [device_id, event_id, token, app_key, album_id, checksum, photo_id]
	 * Response params: [photo_id filesize name thumbnail fullsize meta rank comments can_share can_order has_sizes sizes share_link]
	 * @return void
	 */
	public function actionRankEventPhoto()
	{
		$this->_commonValidate();
		$this->_validateAuth();
		$this->_validateVars(array(
				'album_id' => array(
					'code'		=> '031',
					'message'	=> 'Album id must not be empty',
					'required'	=> TRUE,
				),
				'photo_id' => array(
					'code'		=> '033',
					'message'	=> 'Photo id must not be empty',
					'required'	=> TRUE,
				),
			));
		$this->_getEventAlbum();
		if (!$this->_getEventPhoto());
			$this->_renderError('041', 'Album has no such photo');
		//$this->_getEventPhoto()->rate
	}

	/**
	 * Send comment to event photo
	 * Inquiry params: [device_id, event_id, token, app_key, album_id, photo_id, name, comment]
	 * Response params: [state message comments_number]
	 * @return void
	 */
	public function actionSendEventPhotoComment()
	{
		$this->_commonValidate();
		$this->_validateAuth();
		$this->_validateVars(array(
				'album_id' => array(
					'code'		=> '031',
					'message'	=> 'Album id must not be empty',
					'required'	=> TRUE,
				),
				'photo_id' => array(
					'code'		=> '033',
					'message'	=> 'Photo id must not be empty',
					'required'	=> TRUE,
				),
				'name' => array(
					'code'		=> '034',
					'message'	=> 'Name must not be empty',
					'required'	=> TRUE,
				),
				'comment' => array(
					'code'		=> '035',
					'message'	=> 'Comment must not be empty',
					'required'	=> TRUE,
				),

			));
		$this->_getEventAlbum();
		if (!$this->_getEventPhoto());
			$this->_renderError('041', 'Album has no such photo');
		$obComment = new EventPhotoComment();
		$obComment->name = $_POST['name'];
		$obComment->comment = $_POST['comment'];
		$obComment->photo_id = $this->_getEventPhoto()->id;
		if (!$obComment->validate())
			$this->_render(array(
				'state'				=> FALSE,
				'message'			=> 'Comment validation failed',
				'comments_number'	=> count($this->_getEventPhoto()->comment)
			));
		$obComment->save();
		$this->_render(array(
			'state'				=> TRUE,
			'message'			=> '',
			'comments_number'	=> count($this->_getEventPhoto()->comment)
		));
	}
}