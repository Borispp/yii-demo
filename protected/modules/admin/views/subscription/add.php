<div class="g12">
	<?php echo $this->renderPartial('/_messages/save');?>
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/adm/js/discount.js"></script>
	<div class="form">
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'option-group-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<fieldset>
			<label>General information</label>
			<section>
				<?php echo $form->labelEx($entry,'membership_id'); ?>
				<div>
					<?php echo $form->dropDownList($entry,'membership_id', CHtml::listData($membership, 'id' , 'name')); ?>
					<?php echo $form->error($entry,'membership_id'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'user_id'); ?>
				<div>
					<?php echo $form->dropDownList($entry,'user_id', CHtml::listData($members, 'id' , 'email')); ?>
					<?php echo $form->error($entry,'user_id'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'start_date'); ?>
				<div>
					<?php echo $form->textField($entry,'start_date', array('class' => 'date')); ?>
					<?php echo $form->error($entry,'start_date'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'expiry_date'); ?>
				<div>
					<?php echo $form->textField($entry,'expiry_date', array('class' => 'date')); ?>
					<?php echo $form->error($entry,'expiry_date'); ?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry,'state'); ?>
				<div>
					<?php echo $form->dropDownList($entry,'state', array(
						$entry::STATE_INACTIVE => 'inactive',
						$entry::STATE_ENABLED => 'payed',
						$entry::STATE_ACTIVE => 'active',
					)); ?>
					<?php echo $form->error($entry,'state'); ?>
				</div>
			</section>
		</fieldset>
		<?php echo YsaHtml::adminSaveSection();?>
		<?php $this->endWidget(); ?>
	</div>
</div>