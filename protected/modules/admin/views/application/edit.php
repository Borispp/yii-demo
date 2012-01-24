<div class="g12">
	<?php echo $this->renderPartial('/_messages/save');?>
	<?php echo $this->renderPartial('/_messages/error');?>
	<div class="form">
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'application-edit-form',
		)); ?>
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

<script type="text/javascript">
	$(function(){
		$('#application-view-form a.image').fancybox();
	});
</script>