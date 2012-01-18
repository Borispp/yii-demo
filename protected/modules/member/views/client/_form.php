<style type="text/css">
		#user-events
		{
			background: #ccc;
			width: 100px;
			height: 100px;
		}
		#events
		{
			background: #0cc;
			width: 100px;
			height: 100px;
		}
	</style>
<div class="form standart-form">
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/resources/js/member/client.js"></script>
	<?php $form=$this->beginWidget('YsaMemberForm', array(
		'id'=>'client-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<section class="cf">
		<?php echo $form->labelEx($entry,'name'); ?>
		<div>
			<?php echo $form->textField($entry,'name', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'name'); ?>
		</div>
	</section>

	<section class="cf">
		<?php echo $form->labelEx($entry,'email'); ?>
		<div>
			<?php echo $form->textField($entry,'email', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'email'); ?>
		</div>
	</section>

	<section class="cf">
		<?php echo $form->labelEx($entry,'password'); ?>
		<div>
			<?php echo $form->textField($entry,'password', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'password'); ?>
		</div>
	</section>

	<section class="cf">
		<?php echo $form->labelEx($entry,'phone'); ?>
		<div>
			<?php echo $form->textField($entry,'phone', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'phone'); ?>
		</div>
	</section>

	<section class="cf">
		<?php echo $form->labelEx($entry,'state'); ?>
		<div>
			<?php echo $form->dropDownList($entry, 'state', $entry->getStates()); ?>
			<?php echo $form->error($entry,'state'); ?>
		</div>
	</section class="cf">

	<section class="cf">
		<?php echo $form->labelEx($entry,'description'); ?>
		<div>
			<?php echo $form->textArea($entry,'description', array('cols' => 40, 'rows' => 4)); ?>
			<?php echo $form->error($entry,'description'); ?>
		</div>
	</section>

	<section id="user-events" rel="client-<?php echo $entry->id?>" class="cf">
		<ul>
			<?php $ids = array();foreach($entry->events as $obEvent):$ids[] = $obEvent->id;?>
			<li class="event" id="event-<?php echo $obEvent->id?>"><?php echo $obEvent->name?></li>
			<?php endforeach?>
		</ul>
	</section>

	<section id="events" class="cf">
		<ul>
			<?php foreach($events as $obEvent): if (in_array($obEvent->id, $ids)) continue;?>
			<li class="event" id="event-<?php echo $obEvent->id?>"><?php echo $obEvent->name?></li>
			<?php endforeach?>
		</ul>
	</section>

	<div class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Add' : 'Save'); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>