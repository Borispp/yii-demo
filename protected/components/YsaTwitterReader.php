<?php
class YsaTwitterReader extends CApplicationComponent
{
	/**
	* Fetch twitter messages for specified user.
	* 
	* @param string $username
	* @return array
	*/
	public static function getTweets($username, $limit = 1) {
		if (!$username) {
			return false;
		}
		$username = trim($username, '/ ');
		
		if (preg_match('~https?~si', $username)) {
			preg_match('~([^/]+)$~si', $username, $matches);
			if (isset($matches[1])) {
				$username = $matches[1];
			}
		}
		
		$url = 'http://twitter.com/statuses/user_timeline/' . $username . '.rss';
		
		Yii::import('ext.httpclient.*');
		Yii::import('ext.httpclient.adapter.*');
		$client = new EHttpClient($url, array(
				'maxredirects' => 0,
				'timeout'      => 30,
				'adapter'	   => 'EHttpClientAdapterCurl',
			));
		
		
		$response = $client->request();
		
		$xml = simplexml_load_string($response->getBody());
		
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);
		
		
		$feed = array();
		for ($index = 0; $index < $limit; $index++) {
			if (isset($array['channel']['item'][$index])) {
				$title = preg_replace('~^' . $username . '\:\s~', '', $array['channel']['item'][$index]['title']);
				$title = self::hyperlinks($title);
				$title = self::users($title);
				
				$feed[] = array(
					'tweet' => $title,
					'date'	=> $array['channel']['item'][$index]['pubDate'],
				);
			}
		}
		
		return $feed;
		
	}

	/**
	* Parse message and highlight hyperlinks
	* 
	* @param string $text
	* @return string 
	*/
	public static function hyperlinks($text) {
		$text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" rel=\"external\">$1</a>", $text);
		$text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" rel=\"external\">$1</a>", $text);    
		$text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\">$1</a>", $text);
		$text = preg_replace('/([\.|\,|\:|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/#search?q=$2\"  rel=\"external\">#$2</a>$3 ", $text);
		return $text;
	}

	/**
	* Parse message and highlight users
	* 
	* @param string $text
	* @return string 
	*/
	public static function users($text) {
		return preg_replace('/([\.|\,|\:|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\"  rel=\"external\">@$2</a>$3 ", $text);
	}
}