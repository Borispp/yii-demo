<div class="w" id="subscription-add">
	<section class="box">
		<div class="box-title">
			<h3>Add New Subscription</h3>
			<div class="box-title-button">
			</div>
		</div>
		<div class="box-content subscription-list">
			
			<?php foreach( $membershipList as $memebership ) : ?>
			
				<?php $form=$this->beginWidget('YsaMemberForm', array(
						'id'=>'subscription-add-form',
					)); ?>
				
				<?php echo $form->hiddenField($entry, 'membership_id', array('value' => $memebership->id )); ?>
				
				<section class="part shadow-box">
					<h3><?php echo $memebership->name ?></h3>
					
					<?php if ( $entry->Discount ) : ?>
						<p><del><?php echo $memebership->price() ?></del></p>
						<p><?php echo $memebership->discountedPrice($entry->Discount) ?></p>
					<?php else : ?>
						<p><?php echo $memebership->price() ?></p>
					<?php endif ?>
					
					<div class="button">
						<?php echo YsaHtml::submitButton('Subscribe'); ?>
					</div>
				</section>
			
				<?php $this->endWidget(); ?>
				
			<?php endforeach ?>
			
			<div class="clear"></div>
			
		</div>
	</section>
	
	<section class="box">
		<div class="box-title">
			<h3>Apply discount code</h3>
		</div>
		<div class="box-content">
			
			<?php $form=$this->beginWidget('YsaMemberForm', array(
					'id' => 'subscription-add-form',
					'action' => CHtml::normalizeUrl(array('subscription/discount'))
				)); ?>
			
			<div class="shadow-box">
				<?php if ( $entry->discount ) : ?>
				<h5><?php echo $entry->discount ?> <a class="icon delete i_round_delete" id="discount-delete" title="Cancel discount" >X</a></h5>
				<?php endif ?>
				<section id="discount-input" class="cf <?php echo $entry->discount ? 'hidden' : '' ?>">
					<?php echo $form->labelEx($entry, 'Discount Code', array('class'=>'title')); ?>
					<div>
						<?php echo $form->textField($entry, 'discount', array('autocomplete'=>"off")); ?>
						<?php echo $form->error($entry,'discount'); ?>
						<?php echo YsaHtml::submitButton('Apply'); ?>
					</div>
				</section>
			</div>
			
			<?php $this->endWidget(); ?>
			
		</div>
	</section>
	
</div>

