<div class="g12">
	<?php echo $this->renderPartial('/_messages/save');?>
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/adm/js/discount.js"></script>
	<div class="form">
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'option-group-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<fieldset>
			<label>General Information</label>
			<section>
				<?php echo $form->labelEx($entry, 'membership_id'); ?>
				<div>
					<a href="<?php echo Yii::app()->createUrl('/admin/membership/edit/', array(
						'id'	=> $entry->Membership->id
				))?>"><?php echo $entry->Membership->name?></a>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry, 'user_id'); ?>
				<div>
					<a href="<?php echo Yii::app()->createUrl('/admin/member/view/', array(
						'id'	=> $entry->Member->id
				))?>"><?php echo $entry->Member->name()?></a>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry, 'discount_id'); ?>
				<div>
					<?php if ($entry->Discount):?>
					<a href="<?php echo Yii::app()->createUrl('/admin/discount/edit/', array(
						'id'	=> $entry->Discount->id
				)) ?>"><?php echo $entry->Discount->code?></a> (<?php echo $entry->Discount->summ?>%)
					<?php else:?>
																									   —
					<?php endif?>
				</div>
			</section>
			<section>
				<?php echo $form->labelEx($entry, 'summ'); ?>
				<div>
					<?php echo $entry->summ?> <?php echo Yii::app()->settings->get('paypal_currency')?>
				</div>
			</section>
		</fieldset>
		<fieldset>
			<label>Editable information</label>
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