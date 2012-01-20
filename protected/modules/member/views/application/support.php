<div class="w" id="application-support">
	<section class="box">
		<div class="box-title">
			<h3>Ticket #<?php echo strtoupper($ticket->code); ?> <span class="date">from <?php echo Yii::app()->dateFormatter->formatDateTime($ticket->created, 'medium', 'short'); ?></span></h3>
		</div>
		<div class="box-content">
			<div class="shadow-box">
				
				<div class="replies">
					<ul class="cf">
						<?php foreach ($ticket->replies as $r) : ?>
							<li class="<?php echo $r->replier->role == User::ROLE_ADMIN ? 'admin' : 'member'?>">
								<div class="title">Reply from <?php echo $r->replier->name(); ?></div>
								<span class="added"><?php echo Yii::app()->dateFormatter->formatDateTime($r->created, 'medium', 'short'); ?></span>
								<p><?php echo $r->message; ?></p>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="form standart-form">
					<h3>Add Reply</h3>
					<?php $form=$this->beginWidget('YsaMemberForm', array(
							'id'=>'application-support-reply-form',
					)); ?>
					<section class="cf">
						<?php echo $form->labelEx($reply,'message'); ?>
						<div>
							<?php echo $form->textArea($reply,'message', array('cols' => 40, 'rows' => 4)); ?>
							<?php echo $form->error($reply,'message'); ?>
						</div>
					</section>
					<section class="button">
						<?php echo YsaHtml::submitButton('Add Reply', array('class' => 'blue')); ?>
					</section>
					<?php $this->endWidget(); ?>
				</div>
			</div>
		</div>
	</section>
</div> 