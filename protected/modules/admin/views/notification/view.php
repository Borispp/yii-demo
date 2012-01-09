<div class="g12">
	<div class="form">
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'notification-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<fieldset>
			<label>Message</label>
			<section>
				<label>Title</label>
				<div>
					<?php echo $entry->title?>
				</div>
			</section>
			<section>
				<label>Text</label>
				<div>
					<?php echo $entry->message?>
				</div>
			</section>
		</fieldset>
		<fieldset>
			<label>Recipients</label>
			<?php foreach($entry->notification_user as $obNotificationUser):?>
			<section>
				<label>
					<?php echo CHtml::link($obNotificationUser->user->name(), Yii::app()->createUrl('/admin/membership/edit/', array(
							'id'	=> $obNotificationUser->user->id)))?>
				</label>
				<div>
					<?php echo $obNotificationUser->read ? 'Read' : 'Not read'?>
				</div>
			</section>
			<?php endforeach?>
		</fieldset>
		<?php $this->endWidget(); ?>
	</div>
</div>
