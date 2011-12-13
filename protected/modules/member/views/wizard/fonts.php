<?php echo $event->sender->menu->run(); ?>


<?php $form = $this->beginWidget('YsaMemberForm', array(
        'id'=>'fonts-step-form',
        'enableAjaxValidation'=>false,
)); ?>

    <section>
        <?php echo $form->labelEx($model, 'mainFont'); ?>
        <div>
            <?php echo $form->textField($model, 'mainFont', array()); ?>
            <?php echo $form->error($model,'mainFont'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($model, 'secondFont'); ?>
        <div>
            <?php echo $form->textField($model, 'secondFont', array()); ?>
            <?php echo $form->error($model,'secondFont'); ?>
        </div>
    </section>

    <section class="btn">
        <?php echo YsaHtml::hiddenField('step', $event->step) ?>
        <?php echo YsaHtml::submitButton('Save & Continue');?>
    </section>

<?php $this->endWidget(); ?>
