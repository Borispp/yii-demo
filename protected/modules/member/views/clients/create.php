<h2>Add New Client</h2>

<?php $form=$this->beginWidget('YsaMemberForm', array(
        'id'=>'client-form',
        'enableAjaxValidation'=>false,
)); ?>
    <section>
        <?php echo $form->labelEx($entry,'email'); ?>
        <div>
            <?php echo $form->textField($entry,'email', array('size'=>60,'maxlength'=>100)); ?>
            <?php echo $form->error($entry,'email'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($entry,'first_name'); ?>
        <div>
            <?php echo $form->textField($entry,'first_name',array('size'=>50,'maxlength'=>50)); ?>
            <?php echo $form->error($entry,'first_name'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($entry,'last_name'); ?>
        <div>
            <?php echo $form->textField($entry,'last_name',array('size'=>50,'maxlength'=>50)); ?>
            <?php echo $form->error($entry,'last_name'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($entry,'note'); ?>
        <div>
            <?php echo $form->textArea($entry,'note',array('cols' => 40, 'rows' => 5)); ?>
            <?php echo $form->error($entry,'note'); ?>
        </div>
    </section>

    <section class="btn">
        <?php echo YsaHtml::submitButton('Save');?>
    </section>

<?php $this->endWidget(); ?>