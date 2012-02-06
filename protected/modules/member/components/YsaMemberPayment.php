<?php
interface YsaMemberPayment
{
	public function verify();
	public function getFormUrl();
	public function getOuterId();
	public function getFormFields(PaymentTransaction $transaction, $notifyUrl, $returnUrl);
}