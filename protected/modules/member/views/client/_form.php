<div class="form">
	<script type="text/javascript" src="<?php echo Yii::app()->baseUrl?>/resources/js/member/client.js"></script>
	<?php $form=$this->beginWidget('YsaMemberForm', array(
		'id'=>'client-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<section>
		<?php echo $form->labelEx($entry,'name'); ?>
		<div>
			<?php echo $form->textField($entry,'name', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'name'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($entry,'email'); ?>
		<div>
			<?php echo $form->textField($entry,'email', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'email'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($entry,'password'); ?>
		<div>
			<?php echo $form->textField($entry,'password', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'password'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($entry,'phone'); ?>
		<div>
			<?php echo $form->textField($entry,'phone', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'phone'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($entry,'state'); ?>
		<div>
			<?php echo $form->dropDownList($entry, 'state', $entry->getStates()); ?>
			<?php echo $form->error($entry,'state'); ?>
		</div>
	</section>

	<section>
		<?php echo $form->labelEx($entry,'description'); ?>
		<div>
			<?php echo $form->textArea($entry,'description', array('cols' => 40, 'rows' => 4)); ?>
			<?php echo $form->error($entry,'description'); ?>
		</div>
	</section>
	<section id="membership-section">
		<label>Related Memberships</label>
		<div class="membership">
			<a href="javascript:void(0)" id="add-to-membership">Add to membership</a>
<!--			--><?php //foreach($entry->DiscountMembership as $obDiscountMembership):?>
<!--			<div class="item">-->
<!--				<div class="item-name">--><?php //echo $obDiscountMembership->getMembership()->name?><!--</div>-->
<!--				<input type="text" class="w_20" name="Discount[membership_ids][--><?php //echo $obDiscountMembership->membership_id?><!--]" value="--><?php //echo $obDiscountMembership->amount?><!--"/>-->
<!--				<a href="javascript:void(0)" class="item-delete-link">Delete</a>-->
<!--			</div>-->
<!--			--><?php //endforeach?>
		</div>
	</section>


	<section id="events">
		<ul>
			<?php foreach($events as $obEvent):?>
			<li id="event-<?php echo $obEvent->id?>"><?php echo $obEvent->name?></li>
			<?php endforeach?>
		</ul>
	</section>

	<section class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Add' : 'Save'); ?>
	</section>
	<?php $this->endWidget(); ?>
</div>