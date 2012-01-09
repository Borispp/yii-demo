<div class="g12">
	<div class="form">
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'notification-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<fieldset>
			<label>Message</label>
			<section>
				<?php echo $form->labelEx($entry,'title*'); ?>
				<div>
					<?php echo $form->textField($entry,'title', array('size'=>60,'maxlength'=>50, 'class' => 'w_50')); ?>
					<?php echo $form->error($entry,'title'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'message*'); ?>
				<div>
					<?php echo $form->textArea($entry,'message', array(
						'data-autogrow' => 'true',
						'rows'          => 3,
					)); ?>
					<?php echo $form->error($entry,'message'); ?>
				</div>
			</section>
		</fieldset>
		<fieldset>
			<label>Recipients</label>
			<?php if (!empty($membersError)):?>
			<div class="errorMessage" style="padding: 15px 1%;background: #F9F9F9;">
				You should select at least one recipient
			</div>
			<?php endif?>
			<section>
				<label for="members-all">Send to all members</label>
				<div>
					<?php echo CHTML::checkBox('members-all', false, array('id' => 'members-all')); ?>
				</div>
			</section>
			<section id="members-block">
				<label for="members">Recipients List</label>
				<div>
					<?php echo Chtml::dropDownList('members', '', CHtml::listData($members, 'id', 'email'), array('multiple' => 'multiple', 'id' => 'members')); ?>
				</div>
			</section>
		</fieldset>
		<fieldset>

		</fieldset>
		<?php echo YsaHtml::adminSaveSection();?>
		<?php $this->endWidget(); ?>
	</div>
</div>
