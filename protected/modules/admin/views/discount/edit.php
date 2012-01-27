<div class="g12">
	<?php echo $this->renderPartial('/_messages/save');?>
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/adm/js/discount.js"></script>
	<div class="form">
		<?php $form=$this->beginWidget('YsaDiscountForm', array(
			'id'=>'option-group-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<fieldset>
			<label>General Information</label>

			<section>
				<?php echo $form->labelEx($entry,'code'); ?>
				<div>
					<?php echo $entry->code?>
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
				<?php echo $form->labelEx($entry,'summ*'); ?>
				<div>
					<?php echo $form->textField($entry,'summ', array('maxlength'=>2, 'class' => 'integer')); ?>%
					<?php echo $form->error($entry,'summ'); ?>
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