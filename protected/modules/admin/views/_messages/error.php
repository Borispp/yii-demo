<?php if(Yii::app()->user->hasFlash('error')): ?>
    <div class="alert warning"><?php echo Yii::app()->user->getFlash('error'); ?></div>
<?php endif;?>