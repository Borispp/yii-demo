<?php
use Goutte\Client;
 
class ClientFacebookAuthTest extends CTestCase 
{
	/**
	 *
	 * @var Goutte\Client
	 */
	protected $client;
 
	/**
	 *
	 * @var string
	 */
	protected $app_key;
	
	/**
	 *
	 * @var stdClass
	 */
	protected $fb_user;
	
	/**
	 * Models to clean up
	 * @var array
	 */
	protected $models = array();
	
	public function setUp() 
	{
		parent::setUp();
		$this->client = new Client();
	}
 
	protected function _setupLinkedFBEnvironment()
	{
		$this->app_key = mt_rand();
		$this->fb_user = $this->_createFBTestUser();
		
		$member = new \Member;
		$member->setAttributes( array(
			'email' => mt_rand().'@example.com',
			'state' => \Member::STATE_ACTIVE
		), false);
		$this->assertTrue( $member->save( false ), 'Unable to save Member model' );
		
		$client = new \Client;
		$client->setAttributes(array(
			'email' => mt_rand().'@example2.com',
			'state' => 1,
			'user_id' => $member->id,
			'facebook_id' => $this->fb_user->id
		), false);
		$this->assertTrue( $client->save( false ), 'Unable to save Client model' );
		
		$app = new \Application;
		$app->setAttributes(array(
			'user_id' => $member->id,
			'appkey' => $this->app_key,
			'state' => \Application::STATE_ACTIVE,
		), false);
		$this->assertTrue( $app->save( false ), 'Unable to save Application model' );
		
		// clean up
		$this->models = array( $app, $client, $member );
	}

	protected function _setupNotLinkedFBEnvironment()
	{
		$this->app_key = mt_rand();
		$this->fb_user = $this->_createFBTestUser();
		
		$member = new \Member;
		$member->setAttributes( array(
			'email' => mt_rand().'@example.com',
			'state' => \Member::STATE_ACTIVE
		), false);
		$this->assertTrue( $member->save( false ), 'Unable to save Member model' );
		
		$client = new \Client;
		$client->setAttributes(array(
			'email' => mt_rand().'@example2.com',
			'state' => 1,
			'user_id' => $member->id,
		), false);
		$this->assertTrue( $client->save( false ), 'Unable to save Client model' );
		
		$app = new \Application;
		$app->setAttributes(array(
			'user_id' => $member->id,
			'appkey' => $this->app_key,
			'state' => \Application::STATE_ACTIVE,
		), false);
		$this->assertTrue( $app->save( false ), 'Unable to save Application model' );
		
		// clean up
		$this->models = array( $app, $client, $member );
	}
	
	public function tearDown()
	{
		foreach ( $this->models as $model )
			$model->delete();
		
		$this->_deleteFBTestUser( $this->fb_user );
	}


	// borrowed from http://www.yiiframework.com/wiki/147/functional-tests-independing-from-your-urlmanager-settings
//	public function open($route, $params=array()) 
//	{
//		$url = explode('phpunit', Yii::app()->createUrl($route, $params));
//		return TEST_BASE_URL.$url[1];
//	}
 
	protected function open( $method, $uri, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true )
	{
		$uri = TEST_BASE_URL.$uri;
		$this->client->request( $method, $uri, $parameters, $files, $server, $content, $changeHistory );
		return json_decode( $this->client->getResponse()->getContent() );
	}
	
	public function testErrorOnGetMethodRequestToDefaultAction()
	{
		$response = $this->open( 'GET', 'api/client' );
		$this->assertEquals( 'error', $response->state );
		$this->assertEquals( 'No post data received', $response->response->message );
	}
	
	/**
	 * @link https://developers.facebook.com/docs/test_users/
	 * 
	 * @return stdClass decoded JSON response
	 */
	protected function _createFBTestUser()
	{
		$app_id = Yii::app()->params['oauth']['facebook_app_id'];
		parse_str(
				file_get_contents( "https://graph.facebook.com/oauth/access_token?client_id={$app_id}&client_secret=".Yii::app()->params['oauth']['facebook_app_secret']."&grant_type=client_credentials" ),
				$output
		);
		return json_decode( file_get_contents( "https://graph.facebook.com/{$app_id}/accounts/test-users?installed=true&method=post&permissions=read_stream&access_token={$output['access_token']}" ) );
	}
	
	/**
	 * @link https://developers.facebook.com/docs/test_users/
	 *
	 * @param stdClass $user
	 * @return string
	 */
	protected function _deleteFBTestUser( $user )
	{
		return $user ? file_get_contents( "https://graph.facebook.com/{$user->id}?method=delete&access_token={$user->access_token}" ) : false;
	}
	
	public function testFacebookLoginWithValidData()
	{
		$this->_setupLinkedFBEnvironment();
		
		$response = $this->open( 
				'POST', 
				'api/client/facebooklogin', 
				array( 
					'app_key' => $this->app_key,
					'device_id' => md5(microtime(true)),
					'fb_access_token' => $this->fb_user->access_token,
					'fb_id' => $this->fb_user->id
				)
		);
		
		$this->assertEquals( 'ok', $response->state );
		$this->assertTrue( $response->response->state );
		$this->assertTrue( !empty($response->response->token), 'No access token' );
	}
	
	public function testUnableToLinkFacebookAccountWithoutValidYSAAccessToken()
	{
		$this->_setupNotLinkedFBEnvironment();
		
		$device_id = md5(microtime(true));
		
		// try to login
		$response = $this->open( 
				'POST', 
				'api/client/facebooklogin', 
				array( 
					'app_key' => $this->app_key,
					'device_id' => $device_id,
					'fb_access_token' => $this->fb_user->access_token,
					'fb_id' => $this->fb_user->id
				)
		);
		
		// not FB acc linked
		$this->assertNull( $response->response->token );
		
		// obtain YSA Access Token by registering new user
		$reg_response = $this->open( 
				'POST', 
				'api/client/register', 
				array( 
					'app_key' => $this->app_key,
					'device_id' => $device_id,
					'name' => 'test',
					'email' => mt_rand().'@example.com',
					'password' => 'foo',
				)
		);
		
		$this->assertNotEmpty( $reg_response->response->token );
		
		// try to link
		$link_response = $this->open( 
				'POST', 
				'api/client/linkFacebook', 
				array( 
					'app_key' => $this->app_key,
					'device_id' => $device_id,
					'fb_access_token' => $this->fb_user->access_token,
					'fb_id' => $this->fb_user->id,
					'token' => $reg_response->response->token
				)
		);
		
		$this->assertEquals( 'ok', $link_response->state, var_export( $link_response, true ) );
		$this->assertTrue( $link_response->response->state );
		
		// logout
		$logout_response = $this->open( 
				'POST', 
				'api/client/logout', 
				array( 
					'app_key' => $this->app_key,
					'device_id' => $device_id,
					'token' => $reg_response->response->token
				)
		);
		
		$this->assertEquals( 'ok', $logout_response->state, var_export( $logout_response, true ) );
		$this->assertTrue( $logout_response->response->state );
		
		// try to login again
		$response = $this->open( 
				'POST', 
				'api/client/facebooklogin', 
				array( 
					'app_key' => $this->app_key,
					'device_id' => $device_id,
					'fb_access_token' => $this->fb_user->access_token,
					'fb_id' => $this->fb_user->id
				)
		);
		
		// access token means successfully auth by Facebook
		$this->assertNotNull( $response->response->token );
	}
}