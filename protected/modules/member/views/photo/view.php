<?php echo YsaHtml::pageHeaderTitle('Album ' . $entry->album->name); ?>

<figure>
	<?php echo $entry->full(); ?>
</figure>


<h3>Comments</h3>
<ul>
	<?php foreach ($entry->comments() as $comment) : ?>
	    <?php $this->renderPartial('/photoComment/_listcomment', array(
			'entry' => $comment,
		)); ?>
	<?php endforeach; ?>	
</ul>

<div class="form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
			'id'=>'photo-comment-form',
			'enableAjaxValidation'=>false,
	)); ?>
	<section>
		<?php echo $form->labelEx($entryComment,'comment'); ?>
		<div>
			<?php echo $form->textArea($entryComment,'comment', array('cols' => 40, 'rows' => 5)); ?>
			<?php echo $form->error($entryComment,'comment'); ?>
		</div>
	</section>

	<section class="button">
		<?php echo YsaHtml::submitButton('Add Comment'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>