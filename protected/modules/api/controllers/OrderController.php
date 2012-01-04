<?php
class OrderController extends YsaApiController
{
	/**
	 * Checks order data and return list of missing or blocked photos
	 * Inquiry params: [device_id, app_key, photos -> [id]]
	 * Response params: [state, message, photos]
	 * @return void
	 */
	public function actionCheck()
	{
		$this->_commonValidate();
		if (empty($_POST['photos']) || !count($_POST['photos']))
			return $this->_render(array(
				'state'		=> FALSE,
				'message'	=> 'Order must contain at least one photo',
				'photos'	=> array()
			));
		$photosWithErrors = $this->_checkPhotos($_POST['photos']);
		
		var_dump($photosWithErrors);
		$this->_render(array(
			'state'		=> !(bool)count($photosWithErrors),
			'message'	=> '',
			'photos'	=> $photosWithErrors
		));
	}

	protected function _checkPhotos(array $photos)
	{
		$photosWithErrors = array();
		foreach($photos as $photoData)
		{
			if (!is_array($photoData) || empty($photoData['id']))
			{
				$photosWithErrors[] = array(
					'message'	=> 'No photo id'
				);
				continue;
			}
			$obEventPhoto = EventPhoto::model()->findByPk($photoData['id']);
			if (!is_object($obEventPhoto))
			{
				$photosWithErrors[] = array(
					'id'		=> $photoData['id'],
					'message'	=> 'Photo doesn\'t exists'
				);
				continue;
			}
			if (!$obEventPhoto->isActive())
			{
				$photosWithErrors[] = array(
					'id'		=> $photoData['id'],
					'message'	=> 'Photo is blocked'
				);
				continue;
			}
		}
		return $photosWithErrors;
	}
	/**
	 * Order action. Should be called after actionCheck
	 * Inquiry params: [device_id, app_key, photos -> [id,quantity,size,style], name, email]
	 * Response params: [state, message]
	 * @return void
	 */
	public function actionSend()
	{
		$this->_commonValidate();
		$this->_validateVars(array(
			'photos' => array(
				'code'		=> '090',
				'message'	=> 'Order must contain at least one photo',
				'required'	=> TRUE,
			),
			'name' => array(
				'code'		=> '091',
				'message'	=> 'Client name is required',
				'required'	=> TRUE,
			),
			'email' => array(
				'code'		=> '092',
				'message'	=> 'Client email is required',
				'required'	=> TRUE,
			),
		));
		if (count($this->_checkPhotos($_POST['photos'])) > 0)
			$this->_renderError('093', 'Some photos are missing. Call actionCheck first.');
		$obOrder = new UserOrder();
		$obOrder->user_id = $this->_getApplication()->user_id;
		$obOrder->email = $_POST['email'];
		$obOrder->last_name = $_POST['name'];
		$obOrder->save();

		foreach($_POST['photos'] as $photoData)
		{
			$obPhoto = EventPhoto::model()->findByPk($photoData['id']);
			$obOrder->addPhoto($obPhoto, empty($photoData['quantity']) ? 1 : $photoData['quantity'], empty($photoData['size']) ? 'standart' : $photoData['size'], @$photoData['style']);
		}
		Yii::app()->mailer->From = Yii::app()->settings->get('send_mail_from_email');
		Yii::app()->mailer->FromName = Yii::app()->settings->get('send_mail_from_name');
		Yii::app()->mailer->AddAddress($obOrder->user->email, $obOrder->user->first_name.' '.$obOrder->user->last_name);
		Yii::app()->mailer->AddAddress('rassols@gmail.com', 'Test Testovich');
		Yii::app()->mailer->Subject = 'New order #'.$obOrder->id;
		Yii::app()->mailer->Body = 'Details in attached PDF'.$obOrder->created;
		$pdfPath = $obOrder->getPdfPath();
		Yii::app()->mailer->AddAttachment($pdfPath, basename($pdfPath));
		$this->_render(array(
			'state'		=> Yii::app()->mailer->Send(),
			'message'	=> NULL,
		));
	}
}