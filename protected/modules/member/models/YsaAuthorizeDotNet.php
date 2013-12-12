<?php
class YsaAuthorizeDotNet extends YsaFormModel
{
	public $card_number;
	public $expire_month;
	public $expire_year;
	public $full_name;
	public $phone;
	public $email;

	public function rules()
	{
		return array(
			array('card_number, email, phone, expire_month, expire_year, full_name', 'required'),
			array('expire_month', 'checkDate'),
		);
	}

	public function checkDate()
	{
		if (intval(date('Y')) > intval($this->expire_year) ||
			(intval(date('Y')) == intval($this->expire_year) && intval(date('m')) > intval($this->expire_month)))
		{
			$this->addError('expire_month', 'Your card already expired');
			return FALSE;
		}
		return TRUE;
	}

	public function checkName($attribute,$params)
	{

		$passwd = YsaHelpers::encrypt($this->$attribute);
		if ($passwd !== Yii::app()->controller->member()->password) {
			$this->addError($attribute, 'Incorrect old password.');
		}
		return true;
	}

	public function getFirstName()
	{
		$_tmp = explode(' ', $this->full_name);
		return $_tmp[0];
		$_p['last_name'] = $_tmp[1];
	}

	public function getLastName()
	{
		$_tmp = explode(' ', $this->full_name);
		return $_tmp[1];
	}

	public function getExpireDate()
	{
		return $this->expire_month.'/'.$this->expire_year;
	}

	public function attributeLabels()
	{
		return array(
			'card_number'  => 'Card Number',
			'email'        => 'Email',
			'phone'        => 'Phone',
			'expire_month' => 'Valid Month',
			'expire_year'  => 'Valid Year',
			'full_name'    => 'Name on the Card',
		);
	}

	public function getCompanyName()
	{
		return 'Some company';
	}

	public function getMonths()
	{
		$values = array();
		for($x = 1; $x < 13; $x++)
		{
			$values[$x] = sprintf('%02.0f',$x);
		}
		return $values;
	}

	public function getYears()
	{
		$values = array();
		for($x = intval(date('Y')); $x < date('Y')+6; $x++)
		{
			$values[$x] = $x;
		}
		return $values;
	}
}