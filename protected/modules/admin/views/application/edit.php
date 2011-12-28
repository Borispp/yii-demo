<div class="g12">
	<?php echo $this->renderPartial('/_messages/save');?>
	<div class="form">
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'application-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<?php foreach($options as $section => $properties):?>
		<fieldset>
			<label><?php echo $section?></label>
			<?php foreach($properties as $label => $value):?>
			<section>
				<label><?php echo $label?></label>
				<div><?php echo $value?></div>
			</section>
			<?endforeach?>
		</fieldset>
		<?endforeach?>
		<fieldset>
			<label>Moderator Toolbar</label>
			<section>
				<?php echo $form->labelEx($entry,'state'); ?>
				<div>
					<?php echo $form->dropDownList($entry, 'state', $entry->getStates()); ?>
					<?php echo $form->error($entry,'state'); ?>
				</div>
			</section>
		</fieldset>
		<?php echo YsaHtml::adminSaveSection();?>
		<?php $this->endWidget(); ?>
	</div>
</div>