<?php
interface YsaMemberPayment
{
	public function verify();
	public function getFormUrl();
	public function getOuterId();
	public function getTemplateName();
	public function prepare(PaymentTransaction $transaction);
	public function enableRedirect();
	public function catchNotification();
	public function getFormFields(PaymentTransaction $transaction, $notifyUrl, $returnUrl);
}