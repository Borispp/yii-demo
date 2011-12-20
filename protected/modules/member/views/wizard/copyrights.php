<?php echo $event->sender->menu->run(); ?>

<?php $form = $this->beginWidget('YsaMemberForm', array(
        'id'=>'copyrights-step-form',
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype' => 'multipart/form-data'),
)); ?>

<?/*
    <section>
        <?php echo $form->labelEx($model, 'photographer_info'); ?>
        <div>
            <?php if ($model->photographer_info) : ?>
                <p><img src="<?php echo $model->photographer_info['url']; ?>" alt="" /></p>
            <?php endif; ?>
            <?php echo $form->fileField($model, 'photographer_info', array()); ?>
            <?php echo $form->error($model,'photographer_info'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($model, 'blog_rss'); ?>
        <div>
            <?php echo $form->textField($model, 'blog_rss', array()); ?>
            <?php echo $form->error($model,'blog_rss'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($model, 'facebook'); ?>
        <div>
            <?php echo $form->textField($model, 'facebook', array()); ?>
            <?php echo $form->error($model,'facebook'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($model, 'twitter'); ?>
        <div>
            <?php echo $form->textField($model, 'twitter', array()); ?>
            <?php echo $form->error($model,'twitter'); ?>
        </div>
    </section>
*/?>
    <section>
        <?php echo $form->labelEx($model, 'copyright'); ?>
        <div>
            <?php echo $form->textField($model, 'copyright', array()); ?>
            <?php echo $form->error($model,'copyright'); ?>
        </div>
    </section>

    <section class="btn">
        <?php echo YsaHtml::hiddenField('step', $event->step) ?>
        <?php echo YsaHtml::submitButton('Save & Continue');?>
    </section>

<?php $this->endWidget(); ?>
