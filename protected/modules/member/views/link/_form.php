<div class="form  <?php echo Yii::app()->request->isAjaxRequest ? 'ajax-form' : 'standart-form'?>">
	<?php $form = $this->beginWidget('YsaMemberForm', array(
			'id'=>'studio-add-link-form',
	)); ?>
	<section class="cf">
		<?php echo $form->labelEx($entry,'name'); ?>
		<div>
			<?php echo $form->textField($entry,'name', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'name'); ?>
		</div>
	</section>
	<section class="cf">
		<?php echo $form->labelEx($entry,'url'); ?>
		<div>
			<?php echo $form->textField($entry,'url', array('maxlength' => 100)); ?>
			<?php echo $form->error($entry,'url'); ?>
		</div>
	</section>
	<?php if (StudioLink::TYPE_CUSTOM == $type) : ?>
		<section class="cf">
			<?php echo $form->labelEx($entry,'icon'); ?>
			<?php
				$folder = '';
				if ($this->member()->application) {
					$folder = $this->member()->application->option('style');
				}
				if (!$folder) {
					$folder = 'black';
				}
			?>
			<div id="studio-form-icon-field">
				<ul>
					<?php foreach ($entry->icons($folder) as $icon => $values) : ?>
						<li class="<?php echo $folder?><?php echo $icon == $entry->icon ? ' selected' : ''?>">
							<figure data-icon="<?php echo $icon?>"><img src="<?php echo $values->url; ?>" alt="<?php echo $values->title; ?>"/></figure>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php echo $form->hiddenField($entry,'icon'); ?>
			</div>
		</section>
		<script type="text/javascript">
			$(function(){
				var field = $('#studio-form-icon-field');
				field.find('figure').click(function(e){
					e.preventDefault();
					var figure = $(this);
					figure.parent().addClass('selected').siblings().removeClass('selected');
					field.find('input:hidden').val(figure.data('icon'));
				});
			});
		</script>
	<?php endif; ?>
	<div class="button">
		<?php echo YsaHtml::submitButton($entry->isNewRecord ? 'Add' : 'Save', array('class' => 'blue')); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>