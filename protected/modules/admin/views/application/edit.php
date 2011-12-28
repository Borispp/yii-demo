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
					<?php if ($itunes_logo && $icon):?>
					<?php echo $form->dropDownList($entry, 'state', $entry->getStates()); ?>
					<?php echo $form->error($entry,'state'); ?>
					<?php endif?>
					<?php if (!$itunes_logo):?>
					<div class="errorMessage">No iTunes logo uploaded</div>
					<?php endif?>
					<?php if (!$icon):?>
					<div class="errorMessage">No iOS uploaded</div>
					<?php endif?>
				</div>
			</section>
			<?php if ($icon):?>
			<section>
				<label>Download iPad icon</label>
				<div>
					<a href="<?php echo $icon['url']?>"><?php echo $icon['url']?></a>
				</div>
			</section>
			<?php endif?>
			<?php if ($itunes_logo):?>
			<section>
				<label>Download iTunes logo</label>
				<div>
					<a href="<?php echo $itunes_logo['url']?>"><?php echo $itunes_logo['url']?></a>
				</div>
			</section>
			<?php endif?>
		</fieldset>
		<?php echo YsaHtml::adminSaveSection();?>
		<?php $this->endWidget(); ?>
	</div>
</div>