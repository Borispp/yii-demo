<?php
class Five00pxOAuth extends TwitterOAuth
{
	public $host = "https://api.500px.com/v1/";
	
	public $useragent = '500pxOAuth v0.2.0-beta2';
	
	function accessTokenURL()  {
	  return 'https://api.500px.com/v1/oauth/access_token';	  
	}
	
	function authenticateURL() {
		return 'https://api.500px.com/v1/oauth/authorize';	
	}
	
	function authorizeURL() {
		return 'https://api.500px.com/v1/oauth/authorize';	
	}
	
	function requestTokenURL() {
		return 'https://api.500px.com/v1/oauth/request_token'; 
	}
}