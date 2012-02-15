<?php
/**
 * API controller.
 */
class YsaApiController extends YsaController
{
	/**
	 * @var EventAlbum
	 */
	protected $_obEventAlbum = NULL;

	/**
	 * @var EventPhoto
	 */
	protected $_obEventPhoto = NULL;


	public $layout = '/layouts/main';

	/**
	 * @var Application
	 */
	protected $_obApplication;

	/**
	 * Common validation same for all actions.
	 * IS_POST validation
	 * app_id and device_id params validation
	 * app validation
	 *
	 * @return void
	 */
	protected function _commonValidate()
	{
		if ($_SERVER['REQUEST_METHOD'] != 'POST')
			$this->_renderError(Yii::t('api', 'common_no_post'));
		if (empty($_POST['app_key']))
			$this->_renderError(Yii::t('api', 'common_no_app_key'));
		if (empty($_POST['device_id']))
			$this->_renderError(Yii::t('api', 'common_no_device_id'));
		if (!$this->_validateApp())
			$this->_renderError(Yii::t('api', 'common_no_app'));
	}

	/**
	 * Validate $_POST vars using rules.
	 * {
	 *		filter:			in_set, integer, float
	 * 		filterOptions:	optional filter params
	 * 		code:			error code in case of validation failure
	 * 		message:		error Message in case validation failure
	 * 		required:		if not required error will raise only when var is defined and doesn't match filter
	 * }
	 * @param array $rules
	 * @return void
	 */
	protected function _validateVars(array $rules)
	{
		foreach($rules as $var => $params)
		{
			if (!array_key_exists($var, $_POST))
				if (!empty($params['required']))
					$this->_renderError($params['message']);
				else
					continue;
			if (empty($params['filter']))
				continue;
			switch($params['filter'])
			{
				default:
					continue;
					break;
				case 'integer':
					if (is_int($_POST[$var]))
						continue;
					break;
				case 'float':
					if (is_float($_POST[$var]))
						continue;
					break;
				case 'in_set':
					if (!is_array($params['filterOptions']) || in_array($_POST[$var], $params['filterOptions']))
						continue;
					break;
			}
			$this->_renderError($params['message']);
		}
	}


	/**
	 * Validate application by calling validate model method
	 * @return bool
	 */
	protected function _validateApp()
	{
		return $this->_getApplication();
	}

	/**
	 * @return Application
	 */
	protected function _getApplication()
	{
		if (!$this->_obApplication)
			$this->_obApplication = Application::model()->findByKey(trim($_POST['app_key']));
		return $this->_obApplication;
	}

	/**
	 * Wrapper for _renderError to render array of errors
	 * @param integer $code
	 * @param array $errors
	 * @return void
	 */
	protected function _renderErrors(array $errors)
	{
		$message = '';
		foreach($errors as $key => $error)
			$message .= ($message ? "\n\r" : '').($key ? $key.' - ': '').is_array($error) ? implode(', ', $error) : $error;
		$this->_renderError($message);
	}

	/**
	 * Error output
	 * @param $code
	 * @param string $message
	 * @return void
	 */
	protected function _renderError($message)
	{
		$this->_render(array(
				'message'	=> $message,
			), 'error');
	}

	/**
	 * Render-helper method needed to define html-compatible headers
	 * @param int $status
	 * @param string $body
	 * @param string $content_type
	 * @return void
	 */
	protected function _sendHeaders($content_type = 'application/json')
	{
		header('HTTP/1.1 200 OK');
		header('Content-type: '.$content_type);
	}

	/**
	 * Convertation to JSON and output
	 * @param array $response
	 * @return void
	 */
	protected  function _render(array $response, $state = 'ok')
	{
		$this->_sendHeaders();
		echo json_encode(array(
				'state'		=> $state,
				'response'	=> $response,
			));
		exit;
	}

	/**
	 * @param Event $obEvent
	 * @return array
	 */
	protected function _getEventInformation(Event $obEvent)
	{
		return array(
			'id'            => $obEvent->id,
			'name'          => $obEvent->name,
			'type'          => $obEvent->type(),
			'description'   => $obEvent->description,
			'date'          => $obEvent->date,
			'creation_date' => $obEvent->created,
			'preview'       => $obEvent->previewUrl(),
			'filesize'      => $obEvent->previewFilesize(),
		);
	}

	/**
	 * Rejects requests to child controller index action
	 * @return void
	 */
	public function actionIndex()
	{
		$this->_renderError('Action parameter is required');
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
	protected function _getEventAlbum($isPortfolio = FALSE)
	{
		if (!$this->_obEventAlbum)
		{
			$obEventAlbum = EventAlbum::model()->findByPk($_POST['album_id']);
			if (!$obEventAlbum)
				return $this->_renderError(Yii::t('api', 'event_album_not_found'));
			if (!$obEventAlbum->isActive())
				return $this->_renderError(Yii::t('api', 'event_album_is_blocked'));
			if ($obEventAlbum->event_id != $_POST['event_id'])
				return $this->_renderError(Yii::t('api', 'event_wrong_event_id'));
			if ($isPortfolio)
			{
				if (!$obEventAlbum->event->isPortfolio())
					return $this->_renderError(Yii::t('api', 'event_album_is_portfolio'));
			}
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
				return $this->_renderError(Yii::t('api', 'event_album_not_found'));
			if ($obEventPhoto->album_id != $_POST['album_id'] || !$obEventPhoto->isActive())
				return $this->_renderError(Yii::t('api', 'event_album_access_denied'));
			$this->_obEventPhoto = $obEventPhoto;
		}
		return $this->_obEventPhoto;
	}


	/**
	 * @param EventAlbum $obPhoto
	 * @return array
	 */
	protected function _getEventAlbumInfo(EventAlbum $album)
	{
		$sizes = array();
		if ($album->canOrder() && $album->size())
		{
			foreach($album->sizes as $size)
				$sizes[$size->title] = array(
					'height'	=> $size->height,
					'width'		=> $size->width,
				);
		}
		return array(
			'name'				=> $album->name,
			'date'				=> $album->shooting_date,
			'place'				=> $album->place,
			'album_id'			=> $album->id,
			'preview'			=> $album->previewUrl(),
			'preview_size'		=> $album->previewFilesize(),
			'number_of_photos'	=> count($album->photos),
			'filesize'			=> $album->size(),
			'checksum'			=> $album->getChecksum(),
			'can_order'			=> $album->canOrder(),
			'can_share'			=> $album->canShare(),
			'can_save'			=> $album->canSave(),
			'order_link'		=> $album->order_link,
			'sizes'				=> $sizes
		);
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
			'thumbnail_size'=> $obPhoto->previewFilesize(),
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
	 * Validates vars and checks token match
	 * @return ClientAuth
	 */
	protected function _validateAuth()
	{
		$this->_commonValidate();
		$this->_validateVars(
			array(
				'token'		=> array(
					'message'	=> Yii::t('api', 'client_no_token'),
					'required'	=> TRUE,
					'event_id'	=> array(
						'code'		=> '111',
						'message'	=> 'No event ID found',
						'required'	=> TRUE
					)
				)
			)
		);

		if ($obClientAuth = ClientAuth::model()->authByToken($_POST['token'], $_POST['app_key'], $_POST['device_id']))
			return $obClientAuth->client;

		$this->_renderError(Yii::t('api', 'client_token_auth_failed'));
	}
}