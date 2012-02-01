<?php
class YsaForm extends CActiveForm
{
	public function emailField($model,$attribute,$htmlOptions=array())
	{
		return YsaHtml::activeEmailField($model,$attribute,$htmlOptions);
	}
}