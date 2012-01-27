<div class="g12">
	<div class="form">
		<?php $form=$this->beginWidget('YsaDiscountForm', array(
			'id'=>'option-group-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<fieldset>
			<label>General Information</label>
			<section>
				<?php echo $form->labelEx($entry,'code*'); ?>
				<div>
					<?php echo $form->textField($entry,'code', array('size'=>60,'maxlength'=>50, 'class' => 'w_50')); ?>
					<?php echo $form->error($entry,'code'); ?>
					<a href="javascript:void(0)" onclick="$('#Discount_code').val('<?php echo YsaHelpers::genRandomString(6,'', array('alphaSmall' => 1, 'alphaBig' => 1, 'num' => 1))?>'); $(this).remove()">Generate Random</a>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'summ*'); ?>
				<div>
					<?php echo $form->textField($entry,'summ', array('size'=>12,'maxlength'=>12, 'class' => 'w_20')); ?>%
					<?php echo $form->error($entry,'summ'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'description'); ?>
				<div>
					<?php echo $form->textArea($entry,'description', array(
						'data-autogrow' => 'true',
						'rows'          => 3,
					)); ?>
					<?php echo $form->error($entry,'description'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'state'); ?>
				<div>
					<?php echo $form->checkBox($entry,'state', array(
						'checked' => $entry->state,
					)); ?>
					<?php echo $form->error($entry,'state'); ?>
				</div>
			</section>
			<section id="membership-section">
				<label>Related Memberships</label>

				<?php echo $form->membershipCheckboxList($memberships, $entry) ?>
				
				<div>First input defines total count of discount uses. Symbol "∞" means that discount have infinite number of uses.</div>
				
			</section>
		</fieldset>
		<?php echo YsaHtml::adminSaveSection();?>
		<?php $this->endWidget(); ?>
	</div>
</div>