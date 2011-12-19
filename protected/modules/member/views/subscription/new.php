<?php echo YsaHtml::pageHeaderTitle('New Subscription'); ?>
<div class="form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
		'id'=>'create-event-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<section>
		<?php echo $form->labelEx($entry, 'Membership Plan'); ?>
		<div>
			<?php echo $form->dropDownList($entry, 'membership_id', CHtml::listData($membershipList, 'id', 'name')); ?>
			<?php echo $form->error($entry, 'membership_id'); ?>
		</div>
	</section>
	<section>
		<?php echo $form->labelEx($entry, 'Discount Code'); ?>
		<div>
			<?php echo $form->textField($entry, 'discount'); ?>
			<?php echo $form->error($entry,'discount'); ?>
		</div>
	</section>
	<section class="button">
		<?php echo YsaHtml::submitButton('Subscribe'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>