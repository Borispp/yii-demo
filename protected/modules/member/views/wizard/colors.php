<?php echo $event->sender->menu->run(); ?>


<?php $form = $this->beginWidget('YsaMemberForm', array(
        'id'=>'colors-step-form',
        'enableAjaxValidation'=>false,
)); ?>

    <section>
        <?php echo $form->labelEx($model, 'mainColor'); ?>
        <div>
            <?php echo $form->textField($model, 'mainColor', array()); ?>
            <?php echo $form->error($model,'mainColor'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($model, 'secondColor'); ?>
        <div>
            <?php echo $form->textField($model, 'secondColor', array()); ?>
            <?php echo $form->error($model,'secondColor'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($model, 'backgroundColor'); ?>
        <div>
            <?php echo $form->textField($model, 'backgroundColor', array()); ?>
            <?php echo $form->error($model,'backgroundColor'); ?>
        </div>
    </section>

    <section class="btn">
        <?php echo YsaHtml::hiddenField('step', $event->step) ?>
        <?php echo YsaHtml::submitButton('Save & Continue');?>
    </section>

<?php $this->endWidget(); ?>
