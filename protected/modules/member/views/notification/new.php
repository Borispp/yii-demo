<style type="text/css">
		#notification-events,
		#notification-clients
		{
			background: #ccc;
			width: 100px;
			height: 100px;
		}
		#events,
		#clients
		{
			background: #0cc;
			width: 100px;
			height: 100px;
		}
	</style>
<div class="w">
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/resources/js/member/notification.js"></script>
	<div class="form">
	<?php $form=$this->beginWidget('YsaMemberForm', array(
		'id'=>'notification-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<section>
	</section>
	<section>
		<?php echo $form->labelEx($entry,'message'); ?>
		<div>
			<?php echo $form->textArea($entry,'message', array('cols' => 40, 'rows' => 4)); ?>
			<?php echo $form->error($entry,'message'); ?>
		</div>
	</section>
	<?php echo $form->hiddenField($entry,'events');?>
	<?php echo $form->hiddenField($entry,'clients');?>
		
	<section id="notification-events" rel="client-<?php echo $entry->id?>">
	</section>

	<section id="events">
		<ul>
			<?php foreach($events as $obEvent):?>
			<li class="event" id="event-<?php echo $obEvent->id?>"><?php echo $obEvent->name?></li>
			<?php endforeach?>
		</ul>
	</section>

	<section id="notification-clients" rel="client-<?php echo $entry->id?>">
	</section>

	<section id="clients">
		<ul>
			<?php foreach($clients as $obClient):?>
			<li class="client" id="client-<?php echo $obClient->id?>"><?php echo $obClient->name?></li>
			<?php endforeach?>
		</ul>
	</section>
	<section class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Add' : 'Save'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>
</div>