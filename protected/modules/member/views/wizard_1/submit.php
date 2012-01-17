<div id="app-wizard" class="w body">
<?php echo $event->sender->menu->run(); ?>
<?php $form = $this->beginWidget('YsaMemberForm', array(
    'id'=>'submit-step-form',
    'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->hiddenField($model, 'finish', array()); ?>
<section class="btn">
    <?php echo YsaHtml::hiddenField('step', $event->step) ?>
    <?php echo YsaHtml::submitButton('Finish');?>
</section>
<?php $this->endWidget(); ?>
</div>