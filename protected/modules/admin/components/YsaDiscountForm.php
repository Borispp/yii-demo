<?php

class YsaDiscountForm extends YsaAdminForm
{
	public function membershipCheckboxList(array $memberships, Discount $discount)
	{
		foreach($discount->DiscountMembership as $discount_memebership)
			$discount_memeberships[$discount_memebership->membership_id] = $discount_memebership->amount();
		
		$output = '';
		foreach($memberships as $membership)
		{
			$output .= '<div class="discount-membership_list">';
			$output .= CHtml::textField(
					"Discount[membership_amounts][{$membership->id}]", 
					isset($discount_memeberships[$membership->id]) ? $discount_memeberships[$membership->id] : '∞', 
					array('size'=>5, 'class'=>"integer", 'id'=>$input_id='amount_membeshipid_'.$membership->id)
			);
			$output .= CHtml::checkBox(
					"Discount[membership_ids][]",
					array_key_exists($membership->id, $discount->membership_ids), 
					array( 
						'value' => $membership->id, 
						'id' => $chbox_id = 'membeshipid_'.$membership->id,
						'data-inputid' => $input_id,
						'class' => 'membership'
					)
			);
			$output .= CHtml::label($membership->name, $chbox_id);
			$output .= '</div>';
		}
		
		$output .= CHtml::error($discount, 'membership_ids');
		
		return $output;
	}
}