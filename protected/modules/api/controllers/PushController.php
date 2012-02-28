<?php
/**
 * Controller for push notifications
 */
class PushController extends YsaApiController
{
	public function init()
	{
		parent::init();
		Yii::createComponent('application.extensions.ApnsPHP.ApnsPHP');
	}

	protected function beforeAction($action)
	{
		$this->_commonValidate();
		return parent::beforeAction($action);
	}

	/**
	 * Store device key. If there is no client_token send blank string
	 * Inquiry params: [app_key, device_id, token, client_token]
	 * Response params: [state, message]
	 * @return void
	 */
	public function actionStoreKey()
	{
		$this->_validateVars(array(
			'token'		=> array(
				'message'	=> Yii::t('api', 'client_no_token'),
				'required'	=> TRUE,
		)));
		$token = new Ipad();
		$token->app_id = $this->_getApplication()->id;
		$token->device_id = $_POST['device_id'];
		$token->token = $_POST['token'];
		if (!empty($_POST['client_token']) && ($client = $this->_validateAuth()))
		{
			$token->client_id = $client->id;
		}
		if (!$token->validate())
		{
			$this->_render(array(
				'state'   => FALSE,
				'message' => 'Error while saving token'
			));
		}
		$token->save();
		$this->_render(array(
			'state'   => TRUE,
			'message' => NULL
		));
	}
}