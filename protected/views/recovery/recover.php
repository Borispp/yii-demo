<?php $this->pageTitle=Yii::app()->name . ' - Recover Password'; ?>


<?php echo YsaHtml::pageHeaderTitle('Restore'); ?>

<?php if(Yii::app()->user->hasFlash('recoveryMessage')): ?>
    <div class="success">
        <?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
    </div>
<?php else: ?>
    <div class="form">
    <?php echo CHtml::beginForm(); ?>
        <?php echo CHtml::errorSummary($form); ?>

        <div class="row">
            <?php echo CHtml::activeLabel($form, 'email'); ?>
            <?php echo CHtml::activeTextField($form, 'email') ?>
            <p class="hint">Please enter your email addres.</p>
        </div>

        <div class="row submit">
            <?php echo CHtml::submitButton('Restore'); ?>
        </div>
    <?php echo CHtml::endForm(); ?>
    </div><!-- form -->
<?php endif; ?>