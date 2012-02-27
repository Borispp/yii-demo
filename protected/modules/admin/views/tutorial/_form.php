<?php $form=$this->beginWidget('YsaAdminForm', array(
		'id'=>'tutorial-form',
)); ?>

	<fieldset>
		<section>
			<?php echo $form->labelEx($entry,'title'); ?>
			<div>
				<?php echo $form->textField($entry,'title', array('size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($entry,'title'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($entry,'slug'); ?>
			<div>
				<?php echo $form->textField($entry,'slug', array('size'=>60,'maxlength'=>50)); ?>
				<?php echo $form->error($entry,'slug'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($entry,'cat_id'); ?>
			<div>
				<?php echo $form->dropDownList($entry,'cat_id', YsaHtml::listData($entry->getCategories(), 'id' , 'name')); ?>
				<?php echo $form->error($entry,'cat_id'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($entry,'state'); ?>
			<div>
				<?php echo $form->dropDownList($entry, 'state', $entry->getStates()); ?>
				<?php echo $form->error($entry,'state'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($entry,'content'); ?>
			<div>
				<?php echo $form->textArea($entry,'content', array(
					'class' => 'html',
					'rows'  => 12,
				)); ?>
				<?php echo $form->error($entry,'content'); ?>
			</div>
		</section>

	</fieldset>
	<?php echo YsaHtml::adminSaveSection();?>
<?php $this->endWidget(); ?>