<div class="g12">
	<?php echo $this->renderPartial('/_messages/save');?>
	<?php echo $this->renderPartial('/_messages/error');?>
	<div class="form">
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'application-moderate-form',
		)); ?>
		<?php foreach($options as $section => $properties):?>
			<fieldset>
				<label><?php echo $section?></label>
				<?php foreach($properties as $label => $value):?>
				<section>
					<label><?php echo $label?></label>
					<div><?php echo $value?></div>
				</section>
				<?endforeach?>
			</fieldset>
		<?endforeach?>
		<fieldset>
			<label>Download</label>
			<section>
				<label>&nbsp;</label>
				<div>
					<p>
						Application Key<br/>
						<code>
							<strong class="big"><?php echo $entry->appkey; ?></strong>
						</code>
					</p>
					
					<?php if (!$itunes_logo):?>
						<div class="errorMessage">No iTunes logo uploaded</div>
					<?php endif?>
					<?php if (!$icon):?>
						<div class="errorMessage">No iOS uploaded</div>
					<?php endif?>
					<?php if (!$splash):?>
						<div class="errorMessage">No Splash uploaded</div>
					<?php endif?>
					<?php if ($icon):?>
						<?php echo YsaHtml::link('Download iPad Icon', array('application/download/id/' . $entry->id . '/image/icon'), array('class' => 'btn')); ?>
					<?php endif?>
					<?php if ($itunes_logo):?>
						<?php echo YsaHtml::link('Download iTunes Logo', array('application/download/id/' . $entry->id . '/image/itunes_logo'), array('class' => 'btn')); ?>
					<?php endif?>
					<?php if ($splash):?>
						<?php echo YsaHtml::link('Download Splash', array('application/download/id/' . $entry->id . '/image/splash_bg_image'), array('class' => 'btn')); ?>
					<?php endif?>
				</div>
			</section>
		</fieldset>
		
		<?php $this->endWidget(); ?>
		
		<?php $this->renderPartial('_moderatorToolbar', array(
			'entry' => $entry,
		));; ?>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#application-moderate-form a.image').fancybox();
		$('#application-approve-button').click(function(e){
			e.preventDefault();
			var link = $(this);
			$.confirm('Are you sure you want to APPROVE this Application?', function(){
				window.location.href = link.attr('href');
			});
		});
	});
</script>