<?php

class DiscountTest extends CDbTestCase
{
	/**
	 * @var Discount
	 */
	protected $discount;
	
	public function setUp() 
	{
		parent::setUp();
		
		$this->discount = new Discount;
		$this->discount->setAttributes(array(
			'code' => mt_rand(),
			'state' => 1,
			'summ' => mt_rand(1,2)
		));
		$this->assertTrue($this->discount->save( false ));
	}
	
	public function tearDown()
	{
		$this->discount->delete();
	}
	
	public function testSummIsNotEditable()
	{
		$old_summ = $this->discount->summ;
		$this->discount->summ = mt_rand(5,10);
		$this->assertTrue($this->discount->save(false));
		$this->assertEquals($old_summ, $this->discount->summ);
	}
}
