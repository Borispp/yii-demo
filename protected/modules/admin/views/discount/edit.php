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
					<?php echo $form->textField($entry,'summ', array('size'=>12,'maxlength'=>12, 'class' => 'w_20')); ?>%
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
				<div class="membership">
					<a href="javascript:void(0)" id="add-to-membership">Add to membership</a>
					<?php foreach($entry->DiscountMembership as $obDiscountMembership):?>
					<div class="item">
						<div class="item-name"><?php echo $obDiscountMembership->getMembership()->name?></div>
						<input type="text" class="w_20" name="Discount[membership_ids][<?php echo $obDiscountMembership->membership_id?>]" value="<?php echo $obDiscountMembership->amount?>"/>
						<a href="javascript:void(0)" class="item-delete-link">Delete</a>
					</div>
					<?php endforeach?>
				</div>
			</section>

		</fieldset>
		<?php echo YsaHtml::adminSaveSection();?>
		<?php $this->endWidget(); ?>
	</div>
</div>