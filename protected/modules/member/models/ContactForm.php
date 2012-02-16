<?php
class ContactForm extends YsaFormModel 
{
	public $address;
	
	public $phone;
	
	public $info;
	
	public $name;
	
	const AREA_ROWS = 2;
	
	const AREA_MAXLENGTH = 100;
	
    public function rules() 
    {
        return array(
			array('address, phone, info, name', 'safe'),
			array('address, phone, info, name', 'filter', 'filter' =>  'trim'),
			array('address, phone, info', 'validateRows'),
        );
    }
	
	public function attributeLabels() {
		return array(
			'name'	=> 'Studio Name',
			'info'	=> 'Additional Information',
			'phone' => 'Phone / Working Hours',
		);
	}
	
	public function validateRows($param)
	{
		$rows = explode("\n", $this->{$param});
		
		if (count($rows) > self::AREA_ROWS) {
			$this->addError('param', 'Row limit exceeded. You can use only ' . self::AREA_ROWS . ' rows.');
		}
	}
}