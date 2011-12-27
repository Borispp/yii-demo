<?php
class EventsController extends YsaApiController
{
	protected $_obEventAlbum = NULL;
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
		foreach($obEvent->albums as $obEventAlbum)
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
		$this->_getEventAlbum();
		$obPortfolioAlbum = $this->_getEvent()->user->studio->portfolio->getAlbumById($_POST['album_id']);
		$this->_checkPhotoAlbum($obPortfolioAlbum);
		$this->_render(array(
			'state'			=> $obPortfolioAlbum->checkHash($_POST['checksum']),
			'checksumm'		=> $obPortfolioAlbum->getChecksum(),
		));
	}


/**
 *
  2. {id, action,


  3. {}  geteventalbums

    1. [array] Albums
      1.
  4. {id, action, device_id, event_id, gallery_id, token, checksum}  iseventalbumupdated

  5.
    1. [integer] state
    2. [string] checksum
    3. [integer] filesize
  6. {id, action, device-id, token, event_id, gallery_id}  geteventalbumphotos

    1. [array] images
      1. [integer] photo_id
      2. [link] thumbnail
      3. [link] fullsize
      4. [string] title
      5. [string] meta
      6. [float] rank
      7. [integer] comments_number

  7. {id, action, device-id, token, event_id, gallery_id, photo_id}  geteventphotocomments

    1. [array] comments
      1. [string] name
      2. [date] date
      3. [string] comment
  8. {id, action, device-id, token, event_id, gallery_id, photo_id}  geteventphotorank

    1. [float] rank
  9. {id, action, device-id, token, event_id, gallery_id, photo_id, name, comment}  sendeventphotocomment

    1. [bool] state
    2. [string] message
    3. [integer] comments_number
  10. {id, action, device-id, token, event_id, gallery_id, photo_id, rank}  rankeventphoto

    1. [bool] state
    2. [string] message
    3. [float] rank

 */
}