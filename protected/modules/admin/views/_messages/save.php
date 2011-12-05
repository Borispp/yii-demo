<?php if(Yii::app()->user->hasFlash('entrySaveSuccess')): ?>
    <div class="alert success"><?php echo Yii::app()->user->getFlash('entrySaveSuccess'); ?></div>
<?php endif;?>