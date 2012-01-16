<?php echo YsaHtml::pageHeaderTitle('Login'); ?>

<?php $this->widget('YsaNotificationBar'); ?>

<?php $this->widget('ext.eauth.EAuthWidget', array('action' => 'auth/loginoauth')) ?>

<p>Please fill out the following form with your login credentials:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

    <?php echo CHtml::errorSummary($model); ?>
    
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <div class="row">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
    </div>

	<div class="row">
            <p class="hint"><?php echo CHtml::link('Register', array('register/')); ?> | <?php echo CHtml::link("Lost Password?", array('recovery/')); ?></p>
	</div>
    
    <div class="row rememberMe">
            <?php echo $form->checkBox($model,'rememberMe'); ?>
            <?php echo $form->label($model,'rememberMe'); ?>
            <?php echo $form->error($model,'rememberMe'); ?>
    </div>

    <div class="row buttons">
            <?php echo CHtml::submitButton('Login'); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
