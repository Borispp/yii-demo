<?php
/**
 * Interface give model the ability to pay through Member Payment Controller
 */
interface YsaPayable
{
	/**
	 * Id will be used in transaction to payment gate.
	 * @abstract
	 * @return string
	 */
	public function getPayableId();

	/**
	 * Name will be shown to user
	 * @abstract
	 * @return string
	 */
	public function getPayableName();

	/**
	 * Full product or service price
	 * @abstract
	 * @return float
	 */
	public function getPayablePrice();
	
	/**
	 * Description will be shown to user
	 * @abstract
	 * @return void
	 */
	public function getPayableDescription();
}
