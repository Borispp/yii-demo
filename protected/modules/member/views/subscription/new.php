<div class="w">
	<section class="box">
		<div class="box-title">
			<h3>Add New Subscription</h3>
			<div class="box-title-button">
			</div>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				<div class="form">
					<?php $form=$this->beginWidget('YsaMemberForm', array(
						'id'=>'subscription-add-form',
					)); ?>
					<section class="cf">
						<?php echo $form->labelEx($entry, 'Membership Plan'); ?>
						<div>
							<?php echo $form->dropDownList($entry, 'membership_id', CHtml::listData($membershipList, 'id', 'name')); ?>
							<?php echo $form->error($entry, 'membership_id'); ?>
						</div>
					</section>
					<section class="cf">
						<?php echo $form->labelEx($entry, 'Discount Code'); ?>
						<div>
							<?php echo $form->textField($entry, 'discount'); ?>
							<?php echo $form->error($entry,'discount'); ?>
						</div>
					</section>
					<div class="button">
						<?php echo YsaHtml::submitButton('Subscribe'); ?>
					</div>
					<?php $this->endWidget(); ?>
				</div>
			</div>
		</div>
	</section>
</div>

