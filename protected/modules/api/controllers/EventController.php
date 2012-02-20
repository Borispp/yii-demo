<?php
class EventController extends YsaApiController
{
	/**
	 * @var Client
	 */
	protected $_obClient = NULL;

	/**
	 * Common method to check application key and authorisation
	 * @return void
	 */
	protected function beforeAction($action)
	{
		$this->_commonValidate();
		$this->_validateAuth();
		return parent::beforeAction($action);
	}

	/**
	 * Validates vars and checks token match
	 * @return boolean
	 */
	protected function _validateAuth()
	{
		$this->_obClient = parent::_validateAuth();
		return TRUE;
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
					'message'	=> Yii::t('api', 'common_no_field', array('{field}' => 'password')),
					'required'	=> TRUE,
				),
				'event_id'	=> array(
					'message'	=> Yii::t('api', 'event_no_id'),
					'required'	=> TRUE
				),
			));
		if (!$this->_getEvent()->canBeAddedByClient($this->_obClient, $_POST['password']))
		{
			$this->_render(array(
				'state'		=> FALSE,
				'message'	=> Yii::t('api', 'event_add_wrong_client'),
			));
		}
		if ($this->_obClient->hasPhotoEvent($this->_getEvent()))
		{
			$this->_render(array(
				'state'		=> FALSE,
				'message'	=> 'Event has been already added',
			));
		}
		if (!$this->_obClient->addPhotoEvent($this->_getEvent(), 'client'))
		{
			$this->_render(array(
				'state'		=> FALSE,
				'message'	=> Yii::t('api', 'event_add_failed'),
			));
		}
		$this->_render(array(
			'state'		=> TRUE,
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
				array('state' => TRUE, 'message' => NULL)
			:
				array('state' => FALSE, Yii::t('api', 'event_remove_failed'))
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
		if (!$this->_getEvent()->isPortfolio() && $this->_obClient->hasPhotoEvent($this->_getEvent()))
		{
			$this->_render($this->_getEventInformation($this->_getEvent()));
		}
		$this->_renderError(Yii::t('api', 'event_get_info_failed'));
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
			$params['albums'][] = $this->_getEventAlbumInfo($obEventAlbum);
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
					'message'	=> Yii::t('api', 'event_album_no_id'),
					'required'	=> TRUE,
				),
				'checksum' => array(
					'message'	=> Yii::t('api', 'common_no_checksumm'),
					'required'	=> TRUE,
				),
			));
		$this->_render(array(
				'state'			=> (bool)!$this->_getEventAlbum()->checkHash($_POST['checksum']),
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
					'message'	=> Yii::t('api', 'event_album_no_id'),
					'required'	=> TRUE,
				),
			));
		if (!$this->_getEventAlbum()->photos)
			$this->_renderError(Yii::t('api', 'event_album_no_photos'));
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
					'message'	=> Yii::t('api', 'event_album_no_id'),
					'required'	=> TRUE,
				),
				'photo_id' => array(
					'message'	=> Yii::t('api', 'event_album_photo_no_id'),
					'required'	=> TRUE,
				),
			));
		$this->_getEventAlbum();
		if (!$this->_getEventPhoto())
			$this->_renderError(Yii::t('api', 'event_album_photo_is_wrong'));
		$this->_render($this->_getPhotoInfo($this->_getEventPhoto()));
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
					'message'	=> Yii::t('api', 'event_album_no_id'),
					'required'	=> TRUE,
				),
				'photo_id' => array(
					'message'	=> Yii::t('api', 'event_album_photo_no_id'),
					'required'	=> TRUE,
				),
				'rate'		=> array(
					'message'	=> Yii::t('api', 'event_no_rate'),
					'required'	=> TRUE,
				)
			));
		$this->_getEventAlbum();
		if (!$this->_getEventPhoto())
			$this->_renderError(Yii::t('api', 'event_album_photo_is_wrong'));
		$obEventPhotoRate = EventPhotoRate::model()->findByAttributes(array(
				'device_id'		=> $_POST['device_id'],
				'photo_id'		=> $_POST['photo_id']
			));
		if ($obEventPhotoRate)
			$this->_renderError(Yii::t('api', 'event_already_rated'));
		$obEventPhotoRate = new EventPhotoRate();
		$obEventPhotoRate->photo_id = $_POST['photo_id'];
		$obEventPhotoRate->device_id = $_POST['device_id'];
		$obEventPhotoRate->rate += (int)$_POST['rate'];
		if (!$obEventPhotoRate->validate())
			$this->_render(array(
					'state'		=> FALSE,
					'message'	=> Yii::t('api', 'event_rate_failed'),
					'rank'		=> $this->_getEventPhoto()->rating()
				));
		$obEventPhotoRate->save();
		$this->_render(array(
				'state'	=> TRUE,
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
					'message'	=> Yii::t('api', 'event_album_no_id'),
					'required'	=> TRUE,
				),
				'photo_id' => array(
					'message'	=> Yii::t('api', 'event_album_photo_no_id'),
					'required'	=> TRUE,
				),
				'comment' => array(
					'message'	=> Yii::t('api', 'common_no_field', array('{field}' => 'comment')),
					'required'	=> TRUE,
				),

			));
		$this->_getEventAlbum();
		if (!$this->_getEventPhoto())
			$this->_renderError(Yii::t('api', 'event_album_photo_is_wrong'));
		$obComment = new EventPhotoComment();
		$obComment->comment = $_POST['comment'];
		$obComment->photo_id = $this->_getEventPhoto()->id;
		if (!$obComment->validate())
			$this->_render(array(
					'state'				=> FALSE,
					'message'			=> Yii::t('api', 'event_comment_validation_failed'),
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
	 * Get unread notifications
	 * Inquiry params: [device_id, app_key, token]
	 * Response params: [notifications -> [message, date]]
	 * @return void
	 */
	public function actionGetNotifications()
	{
		$notifications = array();
		foreach(ApplicationNotification::model()->findByClient($this->_obClient, $_POST['device_id']) as $obApplicationNotification)
		{
			$notifications[] = array(
				'message'	=> $obApplicationNotification->message,
				'date'		=> $obApplicationNotification->created,
			);
			$obApplicationNotification->sent($_POST['device_id']);
		}
		$this->_render(array(
				'notifications'	=> $notifications
			));
	}
}