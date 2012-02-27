<?php
class RecoveryClientForm extends RecoveryForm
{
	/**
	 * @var Client
	 */
	protected $_client;

	public function checkexists($attribute, $params)
	{
		if(!$this->hasErrors())
		{
			if (!$this->getClient())
			{
				$this->addError("email", 'The email you entered is not correct');
			}
		}
	}

	public function getClient()
	{
		if (!$this->_client)
		{
			$this->_client = Client::model()->findByAttributes(array('email' => $this->email, 'user_id' => $this->user_id));
		}
		return $this->_client;
	}
}