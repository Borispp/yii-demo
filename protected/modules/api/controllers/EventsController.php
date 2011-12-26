<?php
class ClientController extends YsaApiController
{
	/**
	 * Auth type
	 * @var string
	 */
	protected $_type = 'client';

	protected function _validateAuth()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
			'token'	=> array(
				'code'		=> '006',
				'message'	=> 'No token received',
				'required'	=> TRUE,
		)));
		$this->_checkAuth();
	}

	/**
	 * Action client authorization.
	 * Inquiry params: [app_key, device_id, password, event_id]
	 * Response params: [token, state, message]
	 * @return void
	 */
	public function actionAuth()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
			'password'	=> array(
				'code'		=> '005',
				'message'	=> 'No password received',
				'required'	=> TRUE,
		)));
		if (!$token = ApplicationAuth::model()->authByPassword($_POST['password'], $_POST['app_key'], $_POST['device_id'], $this->_type))
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
	 * Action client authorization.
	 * Inquiry params: [app_key, device_id, password]
	 * Response params: [token, state, message]
	 * @return void
	 */
	public function actionAddEvent()
	{
		$this->_commonValidate();
	}
/**
 *
  1. {id, action, event_id ,password, device_id} addevent
    1. [integer] state
    2. [string] message
    3. [string] token
  2. {id, action,   geteventinfo
    1. [string] name
    2. [string] type
    3. [string] description
    4. [date] date
    5. [date] creation_date

  3. {id, action, device_id, event_id, token}  geteventalbums

    1. [array] Albums
      1. [string] name
      2. [date] date
      3. [string] place
      4. [integer] gallery_id
      5. [link] preview
      6. [integer] number_of_photos
      7. [integer] filesize
      8. [string] checksum
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