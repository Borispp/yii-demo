<?php $this->pageTitle=Yii::app()->name . ' - Error'; ?>

<?php echo YsaHtml::pageHeaderTitle('Error ' . $code); ?>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>