<?php
class YsaApiController extends YsaController
{
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
			$this->_renderError(001,'No post data received');
		if (empty($_POST['app_id']))
			$this->_renderError(002,'No app_id received');
		if (empty($_POST['device_id']))
			$this->_renderError(002,'No device_id received');
		if (!$this->_validateApp())
			$this->_renderError(100,'No app with received app_id');
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
				if ($rules['required'])
					$this->_renderError($params['code'], $params['message']);
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
			$this->_renderError($params['code'], $params['message']);
		}
	}


	/**
	 * Validate application by calling validate model method
	 * @todo use model for validation
	 * @return bool
	 */
	protected function _validateApp()
	{
		if ($_POST['app_id'] == 1)
			return TRUE;
		return FALSE;
	}

	/**
	 * Error output
	 * @param $code
	 * @param $message
	 * @return void
	 */
	protected function _renderError($code, $message)
	{
		$this->_render(array(
				'message'	=> $message,
				'code'		=> $code,
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
}