<h2>Settings</h2>

<?php $form=$this->beginWidget('YsaForm', array(
    'id'=>'settings-form',
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

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

<?php $this->endWidget();?>


<?php $form = $this->beginWidget('YsaForm', array(
    'id'=>'change-password-form',
    'enableAjaxValidation'=>false,
)); ?>

    <section>
        <?php echo $form->labelEx($password,'currentPassword'); ?>
        <div>
            <?php echo $form->textField($password,'currentPassword', array('size'=>60,'maxlength'=>50)); ?>
            <?php echo $form->error($password,'currentPassword'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($password,'newPassword'); ?>
        <div>
            <?php echo $form->textField($password,'newPassword',array('size'=>50,'maxlength'=>50)); ?>
            <?php echo $form->error($password,'newPassword'); ?>
        </div>
    </section>

    <section>
        <?php echo $form->labelEx($password,'repeatPassword'); ?>
        <div>
            <?php echo $form->textField($password,'repeatPassword',array('size'=>50,'maxlength'=>50)); ?>
            <?php echo $form->error($password,'repeatPassword'); ?>
        </div>
    </section>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

<?php $this->endWidget();?>