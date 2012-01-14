<?php
class EventController extends YsaApiController
{
	/**
	 * @var EventAlbum
	 */
	protected $_obEventAlbum = NULL;

	/**
	 * @var EventPhoto
	 */
	protected $_obEventPhoto = NULL;

	/**
	 * @var Client
	 */
	protected $_obClient = NULL;

	/**
	 * Common method to check application key and authorisation
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->_commonValidate();
		$this->_validateAuth();

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
		if ($obClientAuth = ClientAuth::model()->authByToken($_POST['token'], $_POST['app_key'], $_POST['device_id']))
		{
			$this->_obClient = $obClientAuth->client;
			return TRUE;
		}
		$this->_renderError(101, 'Authorization by token failed');
	}

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
			if ($obEventAlbum->event_id != $_POST['event_id'] || !$obEventAlbum->isActive())
				return $this->_renderError('021', 'Access to event album restricted');
			$this->_obEventAlbum = $obEventAlbum;
		}
		return $this->_obEventAlbum;
	}

	/**
	 * @param Event $obEvent
	 * @return array
	 */
	protected function _getEventInformation(Event $obEvent)
	{
		return array(
			'id'			=> $obEvent->id,
			'name'			=> $obEvent->name,
			'type'			=> $obEvent->type(),
			'description'	=> $obEvent->description,
			'date'			=> $obEvent->date,
			'creation_date'	=> $obEvent->created
		);
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
			if ($obEventPhoto->album_id != $_POST['album_id'] || !$obEventPhoto->isActive())
				return $this->_renderError('021', 'Access to event album restricted');
			$this->_obEventPhoto = $obEventPhoto;
		}
		return $this->_obEventPhoto;
	}

	/**
	 * Add new event to client
	 * Inquiry params: [app_key, device_id, password, event_id, token]
	 * Response params: [state, message]
	 * @return void
	 */
	public function actionAddEvent()
	{
		$this->_validateVars(array(
				'password'	=> array(
					'code'		=> '005',
					'message'	=> 'No password received',
					'required'	=> TRUE,
				),
				'event_id'	=> array(
					'code'		=> '006',
					'message'	=> 'No event ID found',
					'required'	=> TRUE
				),
			));
		if (!$this->_getEvent()->canBeAddedByClient($this->_obClient, $_POST['password']))
		{
			$this->_render(array(
				'state'		=> 0,
				'message'	=> 'Access Denied',
			));
		}
		if (!$this->_obClient->addPhotoEvent($this->_getEvent(), 'client'))
		{
			$this->_render(array(
				'state'		=> 0,
				'message'	=> 'Operation Failed',
			));
		}
		$this->_render(array(
			'state'		=> 1,
			'message'	=> '',
		));
	}

	/**
	 * Action deletes connection between client and event
	 * Inquiry params: [app_key, device_id, token, event_id]
	 * Response params: [state, message]
	 * @return void
	 */
	public function actionRemoveEvent()
	{
		$this->_render(($this->_obClient->removePhotoEvent($this->_getEvent(), 'client')) ?
				array('state' => 1, 'message' => NULL)
			:
				array('state' => 0, 'message' => 'Operation failed')
		);
	}

	/**
	 * Get Event List
	 * Inquiry params: [app_key, device_id, token]
	 * Response params: events->[name,type,description,date,creation_date,filesize,checksumm]
	 * @return void
	 */
	public function actionGetEventList()
	{
		$result = array();
		foreach($this->_obClient->events as $obEvent)
		{
			$result[] = $this->_getEventInformation($obEvent);
		}
		$this->_render(array(
			'events'	=> $result
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
		$this->_validateVars(array(
			'event_id'	=> array(
				'code'		=> '006',
				'message'	=> 'No event ID found',
				'required'	=> TRUE
			),
		));
		if (!$this->_getEvent()->isPortfolio() && $this->_obClient->hasPhotoEvent($this->_getEvent()))
		{
			$this->_render($this->_getEventInformation($this->_getEvent()));
		}
		$this->_renderError('087', 'Access denied');
	}

	/**
	 * Returns event info.
	 * Inquiry params: [device_id, event_id, token, app_key]
	 * Response params: [name,type,description,date,creation_date,filesize,checksumm,can_order,can_share,sizes]
	 * @return void
	 */
	public function actionGetEventAlbums()
	{
		$params = array();
		foreach($this->_getEvent()->albums as $obEventAlbum)
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
				'number_of_photos'	=> count($obEventAlbum->photos),
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
	 * Response params: [state, checksum]
	 * @return void
	 */
	public function actionIsEventAlbumUpdated()
	{
		$this->_validateVars(array(
				'album_id' => array(
					'code'		=> '011',
					'message'	=> 'Album id must not be empty',
					'required'	=> TRUE,
				),
				'checksum' => array(
					'code'		=> '012',
					'message'	=> 'Checksum id must not be empty',
					'required'	=> TRUE,
				),
			));
		$this->_render(array(
				'state'			=> !$this->_getEventAlbum()->checkHash($_POST['checksum']),
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
		$this->_validateVars(array(
				'album_id' => array(
					'code'		=> '031',
					'message'	=> 'Gallery id must not be empty',
					'required'	=> TRUE,
				),
			));
		if (!$this->_getEventAlbum()->photos)
			$this->_renderError('041', 'Album has no photos');
		$params = array();
		foreach($this->_getEventAlbum()->photos as $obPhoto)
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
		if (!$this->_getEventPhoto())
			$this->_renderError('041', 'Album has no such photo');
		$this->_render($this->_getPhotoInfo($this->_getEventPhoto()));
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
	 * Inquiry params: [device_id, event_id, token, app_key, album_id, photo_id, rate]
	 * Response params: [state message rank]
	 * @return void
	 */
	public function actionRankEventPhoto()
	{
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
				'rate'		=> array(
					'code'		=> '034',
					'message'	=> 'Photo rate 0 or 1 is required',
					'required'	=> TRUE,
				)
			));
		$this->_getEventAlbum();
		if (!$this->_getEventPhoto())
			$this->_renderError('041', 'Album has no such photo');
		$obEventPhotoRate = EventPhotoRate::model()->findByAttributes(array(
				'device_id'		=> $_POST['device_id'],
				'photo_id'		=> $_POST['photo_id']
			));
		if ($obEventPhotoRate)
			$this->_renderError('050', 'Photo\'d been already rated');
		$obEventPhotoRate = new EventPhotoRate();
		$obEventPhotoRate->photo_id = $_POST['photo_id'];
		$obEventPhotoRate->device_id = $_POST['device_id'];
		$obEventPhotoRate->rate += (int)$_POST['rate'];
		if (!$obEventPhotoRate->validate())
			$this->_render(array(
					'state'		=> FALSE,
					'message'	=> 'Photo rate validation failed',
					'rank'		=> $this->_getEventPhoto()->rating()
				));
		$obEventPhotoRate->save();
		$this->_render(array(
				'state'	=> 1,
				'rank'	=> $this->_getEventPhoto()->rating()
			));
	}

	/**
	 * Send comment to event photo
	 * Inquiry params: [device_id, event_id, token, app_key, album_id, photo_id, comment]
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
				'comment' => array(
					'code'		=> '035',
					'message'	=> 'Comment must not be empty',
					'required'	=> TRUE,
				),

			));
		$this->_getEventAlbum();
		if (!$this->_getEventPhoto())
			$this->_renderError('041', 'Album has no such photo');
		$obComment = new EventPhotoComment();
		$obComment->comment = $_POST['comment'];
		$obComment->photo_id = $this->_getEventPhoto()->id;
		if (!$obComment->validate())
			$this->_render(array(
					'state'				=> FALSE,
					'message'			=> 'Comment validation failed',
					'comments_number'	=> count($this->_getEventPhoto()->comments)
				));
		$obComment->save();
		$this->_render(array(
				'state'				=> TRUE,
				'message'			=> '',
				'comments_number'	=> count($this->_getEventPhoto()->comments)
			));
	}

	/**
	 * Send comment to event photo
	 * Inquiry params: [device_id, app_key, events -> [event_id,token]]
	 * Response params: [notifications -> [event_id, message, date]]
	 * @return void
	 */
	public function actionGetNotifications()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
				'events' => array(
					'code'		=> '060',
					'message'	=> 'Event list is required',
					'required'	=> TRUE,
				),
			));
		$notifications = array();
		$notificationIterator = array();
		foreach($_POST['events'] as $eventData)
		{
			if (!($obEventAuth = EventAuth::model()->authByToken($eventData['token'], $_POST['app_key'], $eventData['event_id'], $_POST['device_id'])))
			{
				return $this->_renderError(101, 'Authorization by token failed for event '.$eventData['event_id']);
			}
			$applicationNotifications = ApplicationNotification::model()->findByApplicationAndEvent($obEventAuth->application, $obEventAuth->event);
			$applicationNotifications = is_object($applicationNotifications) ? array($applicationNotifications) : $applicationNotifications;
			$notificationIterator += $applicationNotifications;
		}
		foreach($notificationIterator as $obApplicationNotification)
		{
			if ($obApplicationNotification)
			$notifications[] = array(
				'event_id'	=> $obApplicationNotification->event_id,
				'message'	=> $obApplicationNotification->message,
				'date'		=> $obApplicationNotification->created,
			);
			$obApplicationNotification->sent();
		}
		$this->_render(array(
			'notifications'	=> $notifications
		));
	}
}