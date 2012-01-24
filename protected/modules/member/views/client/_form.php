<div class="form standart-form">
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/resources/js/member/client.js"></script>
	<?php $form=$this->beginWidget('YsaMemberForm', array(
		'id'=>'client-form',
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
	<section class="cf">
		<label>Events</label>
		<div>
			<?php echo $form->listBox($entry, 'eventList', YsaHtml::listData($events, 'id', 'name'), array('class' => 'multiselect', 'multiple' => 'multiple', 'options' => $entry->selectedEvents)); ?>
			

			<?/*
			<select multiple='multiple' class="multiselect">
					<?php $ids = array();foreach($entry->events as $obEvent):$ids[] = $obEvent->id;?>
					<li class="ui-state-highlight event" id="event-<?php echo $obEvent->id?>"><?php echo $obEvent->name?></li>
					<?php endforeach?>
			</select>
			<div class="events-block">
				<div class="box-title">
					<h3>Connected</h3>
				</div>
				<div class="box-content">
					<ul id="user-events" class="connectedSortable">
						<?php $ids = array();foreach($entry->events as $obEvent):$ids[] = $obEvent->id;?>
						<li class="ui-state-highlight event" id="event-<?php echo $obEvent->id?>"><?php echo $obEvent->name?></li>
						<?php endforeach?>
					</ul>
				</div>
			</div>
			<div class="events-block">
				<div class="box-title">
					<h3>Available</h3>
				</div>
				<div class="box-content">
					<ul id="events" class="connectedSortable">
						<?php foreach($events as $obEvent): if (in_array($obEvent->id, $ids)) continue;?>
						<li class="ui-state-highlight event" id="event-<?php echo $obEvent->id?>"><?php echo $obEvent->name?></li>
						<?php endforeach?>
					</ul>
				</div>
			</div>
			 * <input type="hidden" name="events" id="events-input" value="<?php echo implode(',', $ids)?>"/>
			 */?>
		</div>
		
	</section>

	<div class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Add' : 'Save', array('class' => 'blue')); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>