<?php
interface YsaPaymentTransaction
{
	public function processSuccess();
	public function getUrl();
	public function getMember();
}