<?php

class Zendesk extends CModel
{
	/**
	 * @var ZendeskAPI
	 */
	protected $api;
	
	public function attributeNames()
	{
		return array();
	}
	
	public function __construct() 
	{
		Yii::import('application.vendors.*');
		require_once('zendesk/Zendesk.lib.php');
		
		$this->api = new ZendeskAPI(
			Yii::app()->settings->get('zendesk_account'),
			Yii::app()->settings->get('zendesk_user'),
			Yii::app()->settings->get('zendesk_password'),
			true, 
			true
		);
		$this->api->set_output(ZENDESK_OUTPUT_JSON);
	}
	
	/**
	 * Generates external URI to request(ticket)
	 *
	 * @param integer $id
	 * @return string
	 */
	public static function requestsUrl($id)
	{
		$account = Yii::app()->settings->get('zendesk_account');
		return "https://{$account}.zendesk.com/requests/{$id}";
	}
	
	public static function newRequestURL()
	{
		$account = Yii::app()->settings->get('zendesk_account');
		return "https://{$account}.zendesk.com/requests/new";
	}
	
	/**
	 *
	 * @param string $date returned by API
	 * @param string $format DateTime output format
	 * @return string date 
	 */
	public static function date($date, $format)
	{
		// 2012/02/13 02:31:50 -0900
		$dt = DateTime::createFromFormat('Y/m/d H:i:s O', $date);
		
		$errors = DateTime::getLastErrors();
		if ($errors['error_count'] > 0)
			return $date;
		
		return $dt->format($format);
	}
	
	/**
	 *
	 * @param string $page
	 * @param array $args
	 * @return stdClass
	 * @throws CException 
	 */
	protected function get($page, $args = array())
	{
		$result = $this->api->get($page, $args);
//		if (!$result)
//			throw new CException("Zendesk API request error: page={$page}, args=".var_export($args,true));

		return $this->readJSON($result);
	}
	
	/**
	 *
	 * @return string|stdClass 
	 */
	protected function readJSON($string)
	{
		$decoded = json_decode($string);
		
		$msg = 'JSON error: ';
		
		if (function_exists('json_last_error')) {
			switch (json_last_error()) 
			{
				case JSON_ERROR_NONE:
					return $decoded;
				break;
				case JSON_ERROR_DEPTH:
					$msg .= 'Maximum stack depth exceeded';
				break;
				case JSON_ERROR_STATE_MISMATCH:
					$msg .= 'Underflow or the modes mismatch';
				break;
				case JSON_ERROR_CTRL_CHAR:
					$msg .= 'Unexpected control character found';
				break;
				case JSON_ERROR_SYNTAX:
					$msg .= 'Syntax error, malformed JSON';
				break;
				case JSON_ERROR_UTF8:
					$msg .= 'Malformed UTF-8 characters, possibly incorrectly encoded';
				break;
				default:
					$msg .= 'Unknown error';
				break;
			}
		} else {
			return $decoded ? $decoded : 'Unknown error';
		}
		

		
		throw new CException($msg);
	}
	
	public static function deleteRequestsCache($member_email)
	{
		Yii::app()->cache->delete(self::requestsCacheKey($member_email));
	}
	
	protected static function requestsCacheKey($member_email)
	{
		return 'zendesk_requests_'.$member_email;
	}
	
	/**
	 * @link http://www.zendesk.com/support/api/tickets
	 * @param string $member_email
	 * @return stdClass 
	 */
	public function requests($member_email)
	{
		try
		{	
			$key = self::requestsCacheKey($member_email);
			$data = Yii::app()->cache->get($key);
		
			if($data === false)
			{
				$data = $this->get('requests', array(
					'on-behalf-of' => $member_email
				));

				Yii::app()->cache->set($key, $data); // infinit lifetime
			}
			else error_log('Cache hit');
			return $data;
		}
		catch(CException $e)
		{
			Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'zendesk');
			return array();
		}
	}
}