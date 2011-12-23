<div class="form">
    <?php $form=$this->beginWidget('YsaMemberForm', array(
            'id'=>'create-app-form',
            'enableAjaxValidation'=>false,
    )); ?>

    <section>
        <?php echo $form->labelEx($app,'name'); ?>
        <div>
            <?php echo $form->textField($app,'name', array('maxlength' => 100)); ?>
            <?php echo $form->error($app,'name'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($app,'info'); ?>
        <div>
            <?php echo $form->textArea($app,'info', array('cols' => 40, 'rows' => 4)); ?>
            <?php echo $form->error($app,'info'); ?>
        </div>
    </section>

    <section class="button">
        <?php echo YsaHtml::submitButton('Create'); ?>
    </section>

    <?php $this->endWidget(); ?>
</div>