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
		if (!$result)
			throw new CException("Zendesk API request error: page={$page}, args=".var_export($args,true));
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
		
		throw new CException($msg);
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
			return $this->get('requests', array(
				'on-behalf-of' => $member_email
			));
		}
		catch(CException $e)
		{
			Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'zendesk');
			throw $e;
		}
	}
}