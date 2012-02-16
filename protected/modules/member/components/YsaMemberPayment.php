<?php
interface YsaMemberPayment
{
	public function verify();
	public function getFormUrl();
	public function getOuterId();
	public function getTemplateName();
	public function catchNotification();
	public function createTransaction($type = NULL, $summ = NULL, $itemId = NULL);
	public function getFormFields($type, $summ, $item_id, $notifyUrl, $returnUrl);
}