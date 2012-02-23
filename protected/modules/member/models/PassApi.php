<?php

class PassApi extends YsaFormModel
{
	const PHOTO_SIZE_THUMBNAIL = 't'; //up to 200px
	const PHOTO_SIZE_MEDIUM = 'm'; //up to 800px
	const PHOTO_SIZE_LARGE = 'l'; //up to 1400px
	
	protected static $photo_sizes = array(
		self::PHOTO_SIZE_THUMBNAIL,
		self::PHOTO_SIZE_MEDIUM,
		self::PHOTO_SIZE_LARGE,
	);
	
	public $email;
    public $password;
	
    public function rules() 
    {
        return array(
            array('email, password', 'required'),
            array('email', 'email'),
        );
    }
	
	protected static function json_decode($json, $toAssoc = false)
    {
		$result = json_decode($json, $toAssoc);
		
		if (function_exists('json_last_error'))
		{
			switch (json_last_error()) 
			{
				case JSON_ERROR_NONE:
				break;
				case JSON_ERROR_DEPTH:
					$error = ' - Maximum stack depth exceeded';
				break;
				case JSON_ERROR_STATE_MISMATCH:
					$error = ' - Underflow or the modes mismatch';
				break;
				case JSON_ERROR_CTRL_CHAR:
					$error = ' - Unexpected control character found';
				break;
				case JSON_ERROR_SYNTAX:
					$error = ' - Syntax error, malformed JSON';
				break;
				case JSON_ERROR_UTF8:
					$error = ' - Malformed UTF-8 characters, possibly incorrectly encoded';
				break;
				default:
					$error = ' - Unknown error';
				break;
			}
		}

        if (!empty($error) || $result === null)
            throw new RuntimeException('JSON Error: '.$error);        
        
        return $result;
    }
	
	/**
	 * @param string $uri
	 * @param boolean $get_method
	 * @param array $post_data
	 * @throws RuntimeException 
	 */
	protected function run($uri, $get_method = true, array $post_data = array())
	{
		$curl = new Curl;
		$curl->options = array('setOptions' => array(CURLOPT_SSL_VERIFYPEER => false));
		
		$result = $curl->run($uri, $get_method, $post_data);
		
		if ($curl->error_code)
		{
			Yii::log('cURL error #'.$curl->error_code.' msg:'.$curl->error_string, CLogger::LEVEL_ERROR, 'pass_api');
			throw new RuntimeException;
		}
		
		try
		{
			$output = $this->json_decode($result);
		}
		catch(RuntimeException $e)
		{
			Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'pass_api');
			throw $e;
		}
		
		if (isset($output->error))
		{
			// invalid session
			if ($output->error_id == 105)
			{
				static $counter;
				$counter++;

				if ($this->authorize() && $counter <= 3)
				{
					$args = func_get_args();
					return call_user_func_array(__METHOD__, $args);
				}
			}
			
			$this->addError('email', $output->message);
			throw new RuntimeException($output->message, $output->error_id );
		}

		return $output;
	}
	
	/**
	 * Authenticate and store session key on success
	 * 
	 * @return true
	 */
	public function authorize()
	{
		try
		{
			/** 
			* Required: email,password or fb_access_token
			* Returns: first_name,last_name,fb_id,session
			* Errors:
			*   Authentication Error - ID: 102
			*   {“error”:”true”,”error_id”:”102”,”message”:[a short error message]}
			*   Account Not Found - ID: 108
			*   {“error”:”true”,”error_id”:”108”,”message”:[a short error message]}
			*   Bad Facebook Token Error - ID: 106
			*   {“error”:”true”,”error_id”:”106”,”message”:[a short error message]}
			*/
			$output = $this->run(
				'https://api.passpremier.com/service/mobile/login', 
				false, 
				array(
					'email' => $this->email,
					'password' => $this->password
				)
			);
			
			if (isset($output->error))
			{
				$this->addError('email', 'PASS: '.$output->message);
				return false;
			}

			self::storeSession($output->session);
			return true;
		}
		catch(RuntimeException $e)
		{
			Yii::log('PASS authorize error ['.$this->email.']: '.$e->getMessage(), CLogger::LEVEL_ERROR, 'pass_api');
			return false;
		}
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function isAuthorized()
	{
		return (bool) self::session();
	}
	
	protected function storeSession($value)
	{
		Yii::app()->session['passapi_session'] = $value;
	}
	
	/**
	 *
	 * @return boolean
	 * @throws RuntimeException 
	 */
	protected function session()
	{
		if (!empty(Yii::app()->session['passapi_session']))
			return Yii::app()->session['passapi_session'];
		
		if (!$this->authorize())
			throw new RuntimeException('PASS: Unable to authorize ['.$this->email.']');
		
		return true;
	}
	
	public function link(Member $member)
	{
		//TODO: workaround to not store the password
		$member->editOption(UserOption::PASS_EMAIL, $this->email);
		$member->editOption(UserOption::PASS_PASSWORD, $this->password);
	}
	
	/**
	 * @param Member $member
	 * @return boolean
	 */
	public function unlink(Member $member)
	{
		//TODO: workaround to not store the password
		return $member->deleteOptions(array(
			UserOption::PASS_EMAIL,
			UserOption::PASS_PASSWORD
		));
	}
	
	/**
	 *
	 * @param Member $member
	 * @param boolean $load_data true to load linked account info into model
	 * @return boolean 
	 */
	public function isLinked(Member $member, $load_data = false)
	{
		$email = $member->option(UserOption::PASS_EMAIL);
		$password = $member->option(UserOption::PASS_PASSWORD);
		
		if ($load_data)
		{
			$this->email = $email ? $email : null;
			$this->password = $password ? $password : null;
		}
		
		return !empty($email) && !empty($password);
	}
	
	/**
	 * API Response example
	 * 
	 * {
     * "first_name": "Pass",
     * "last_name": "Premier",
     * "fb_id": "0",
     * "profile_pic": "",
     * "base_img_url": "http:\/\/data.passpremier.com",
     * "events": [{
     *     "title": "Mark + Kelsey Wedding",
     *     "event_date": "2011-05-20",
     *     "client_name": "Mark and Kelsey",
     *     "key": "lRg32100003",
     *     "key_img": "DBsPq100043",
     *     "logo_img": "c8CcK100862",
     *     "dl_max_size": "x",
     *     "access": "0",
     *     "fb_share": "1",
     *     "photog_website": "grayphotograph.com",
     *     "photog_credit": "Gray Photography",
     *     "settings": null,
     *     "creator_id": "10002",
     *     "level": "1",
     *     "has_logo_img": "0"
     * }]
     * }
	 * 
	 * @return stdClass
	 */
	public function eventList()
	{
		try
		{
			return $this->run('http://api.passpremier.com/service/mobile/events/session/'.self::session());
		}
		catch(RuntimeException $e)
		{
			return (object) array('base_img_url' => '', 'events' => array());
		}
	}
	
	/**
	 * API example response
	 * 
	 * {
	 * "event_key": "lRg32100003",
     * "title": "Mark + Kelsey Wedding",
     * "event_date": "2011-05-20",
     * "client_name": "Mark and Kelsey",
     * "dl_max_size": "l",
     * "key_img": "DBsPq100043",
     * "logo_img": "c8CcK100862",
     * "photog_website": "grayphotograph.com",
     * "photog_credit": "Gray Photography",
     * "collections": [{
     *     "c_id": "100006",
     *     "title": "Ceremony",
     *     "key": "z4tnb100006",
     *     "key_img": "DBsPq100043",
     *     "type": "0",
     *     "dl_max_size": "l",
     *     "creator_id": "10002",
     *     "access": "0",
     *     "images": [{
     *         "c_id": "100006",
     *         "key": "DBsPq100043"
     *     }]
	 * }]
	 * }
	 * 
	 * @param string $key
	 * @return stdClass
	 */
	public function eventInfo($key)
	{
		try
		{
			return $this->run('http://api.passpremier.com/service/mobile/eventinfo/event_key/'.$key.'/'.self::session());
		}
		catch(RuntimeException $e)
		{
			// empty fake stdClass obj
			return (object) array('collections' => array());
		}
	}
	
	/**
	 *
	 * The URLs used to access an image from PASS is in the form of the base_img_url slash event key slash image key and then a size letter dot jpg.
	 * 
	 * @param string $base_img_url
	 * @param string $event_id
	 * @param string $img_id
	 * @param string $img_size
	 * @throws InvalidArgumentException
	 * @return string 
	 */
	protected function formatImageUrl($base_img_url, $event_id, $img_id, $img_size)
	{
		if (!in_array($img_size, self::$photo_sizes))
			throw new InvalidArgumentException('Unknown photo size ['.$img_size.']');
		
		return "{$base_img_url}/{$event_id}/{$img_id}{$img_size}.jpg";
	}
	
	/**
	 * Return array of Albums in custom format
	 * 
	 * @return array 
	 */
	public function ysaAlbumList()
	{
		$albums = array();
		foreach($this->eventList()->events as $event)
		{
			$event_info = $this->eventInfo($event->key);
			foreach($event_info->collections as $collection)
			{
				$albums[] = (object) array(
					'title' => $event->title .' / '. $collection->title,
					'key' => $event->key.'|'.$collection->c_id,
					'photo_count' => count($collection->images)
				);
			}
		}
		return $albums;
	}
	
	/**
	 * 
	 * @param string $event_id
	 * @param string $collection_id
	 * @param string $photo_size
	 * @return array in custom format used by EventAlbum::importPassAlbum
	 */
	public function loadPhotoSet($event_id, $collection_id, $photo_size)
	{
		$photo_set = array();
		$event_list = $this->eventList();
		foreach($event_list->events as $event)
		{
			if ($event->key != $event_id)
				continue;
			
			$photo_set = array(
				'Title' => $event->title,
				'LastUpdated' => $event->event_date,
				'URL' => ''
			);
			
			$event_info = $this->eventInfo($event->key);
			foreach($event_info->collections as $collection)
			{
				if ($collection->c_id != $collection_id)
					continue;
				
				$photo_set['Title'] .= ' / '.$collection->title;
				$photo_set['PhotoCount'] = count($collection->images);
				$photo_set['CoverImgKey'] = $collection->key_img;
				foreach ($collection->images as $image)
				{
					$photo_set['photoSetImages'][] = array(
						'URL' => self::formatImageUrl($event_list->base_img_url, $event->key, $image->key, $photo_size),
						'FileName' => $image->key,
						'Key' => $image->key,
						'Date' => $event->event_date
					);
				}
				
				break 2;
			}
		}
		return $photo_set;
	}
	
	/**
	 * Change requesting photo size in given URL
	 *
	 * @param string $url
	 * @param string $new_photo_size
	 * @return string
	 * @throws InvalidArgumentException 
	 */
	public static function changeImageSizeInUrl($url, $new_photo_size)
	{
		if (!in_array($new_photo_size, self::$photo_sizes))
			throw new InvalidArgumentException('Unknown photo size ['.$new_photo_size.']');
		
		return preg_replace('~('.implode('|', self::$photo_sizes).')\.jpg$~', $new_photo_size.'.jpg', $url, 1);
	}
}