<?php $this->beginContent('//layouts/main'); ?>

<?php if(Yii::app()->user->hasFlash('success')): ?>
    <div class="flash success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php elseif (Yii::app()->user->hasFlash('notice')): ?>
    <div class="flash notice">
        <?php echo Yii::app()->user->getFlash('notice'); ?>
    </div>
<?php elseif (Yii::app()->user->hasFlash('error')): ?>
    <div class="flash error">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif;?>

<?php echo $content; ?>

<?php $this->endContent(); ?>