<?php if(Yii::app()->user->hasFlash('registration')): ?>
<div class="success">
    <?php echo Yii::app()->user->getFlash('registration'); ?>
</div>
<?php else: ?>

    <div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'registration-form',
            'enableAjaxValidation'=>false,
    )); ?>

        <p class="note">Fields with <span class="required">*</span> are required.</p>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email'); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'password'); ?>
            <?php echo $form->passwordField($model,'password'); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'verifyPassword'); ?>
            <?php echo $form->passwordField($model,'verifyPassword'); ?>
            <?php echo $form->error($model,'verifyPassword'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'first_name'); ?>
            <?php echo $form->textField($model,'first_name'); ?>
            <?php echo $form->error($model,'first_name'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'last_name'); ?>
            <?php echo $form->textField($model,'last_name'); ?>
            <?php echo $form->error($model,'lastlast_nameName'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'verifyCode'); ?>
            <?php $this->widget('CCaptcha'); ?>
            <?php echo $form->textField($model,'verifyCode'); ?>
            <?php echo $form->error($model,'verifyCode'); ?>
        </div>
        <div class="row buttons">
            <?php echo CHtml::submitButton('Submit'); ?>
        </div>
    <?php $this->endWidget(); ?>
    </div><!-- form -->
<?php endif;?>