<?php echo $event->sender->menu->run(); ?>

<h2>Upload your logo</h2>
<?php $form = $this->beginWidget('YsaMemberForm', array(
        'id'=>'logo-step-form',
        'enableAjaxValidation'=>false,
)); ?>

    <section>
        <?php echo $form->labelEx($model, 'logo'); ?>
        <div>
            <?php echo $form->fileField($model, 'logo', array()); ?>
            <?php echo $form->error($model,'logo'); ?>
        </div>
    </section>

    <section class="btn">
        <?php echo YsaHtml::hiddenField('step', $event->step) ?>
        <?php echo YsaHtml::submitButton('Save & Continue');?>
    </section>

<?php $this->endWidget(); ?>

<h4>Logo requirements</h4>
<ul>
    <li>Acceptable formats</li>
    <li>Minimum size</li>
</ul>
<h2>Logo Preview</h2>

<p>*** preview goes here ***</p>