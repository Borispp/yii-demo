<section class="g6 custom-field" id="customfield_<?php echo $field->id?>">
	<div class="name">
		<input type="text" value="<?php echo $field->name; ?>" placeholder="Field Name" />
	</div>
	<div class="area">
		<textarea cols="40" rows="5"><?php echo $field->value; ?></textarea>
	</div>
	<div class="image" id="custom-field-image-<?php echo $field->id?>">
		<?php if ($field->image) : ?>
			<?php $this->renderPartial('_customImage', array(
				'image' => $field->image(),
				'field'	=> $field,
			)) ?>
		<?php else: ?>
			<?php $this->renderPartial('_customLoad', array(
				'field'	=> $field,
			)) ?>
		<?php endif; ?>
	</div>
	<div class="save r">
		<?php echo YsaHtml::link('Delete', '#', array('class' => 'btn red small del', 'data-id' => $field->id,)); ?>
		<?php echo YsaHtml::link('Sort','#', array('class' => 'btn yellow small sort' , 'data-id' => $field->id,)); ?>
		<?php echo YsaHtml::link('Save', '#', array('class' => 'btn blue small save' , 'data-id' => $field->id,)); ?>
	</div>
	<div class="clearfix"></div>
</section>