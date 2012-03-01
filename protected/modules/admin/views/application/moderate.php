<div class="g12">
	<?php echo $this->renderPartial('/_messages/save');?>
	<?php echo $this->renderPartial('/_messages/error');?>
	
	<p class="r">
		<?php echo YsaHtml::link('Preview', '#', array('class' => 'btn small green', 'id' => 'application-preview-link', 'data-id' => $entry->id)); ?>
	</p>
	
	<div class="form">
		
		<?php $this->renderPartial('_moderatorToolbar', array(
			'entry' => $entry,
		)); ?>
		
		<?php $this->renderPartial('_preview', array(
			'entry' => $entry,
		)); ?>
		
		<?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'application-moderate-form',
		)); ?>
		
		<fieldset>
			<label>Download</label>
			<section>
				<div class="columns">
					<section class="g5">
						<h4>Application Key</h4>
						<p>
							<code>
								<strong class="big"><?php echo $entry->appkey; ?></strong>
							</code>
						</p>
					</section>
					<section class="g2 c">
						<?php if ($itunes_logo):?>
							<?php echo YsaHtml::link($entry->image('itunes_logo') . '<br/>iTunes Logo', array('application/download/id/' . $entry->id . '/image/itunes_logo'), array('class' => 'btn w_60')); ?>
						<?php else: ?>
							<div class="errorMessage">No iTunes logo uploaded</div>
						<?php endif?>
					</section>
					<section class="g2 c">
						<?php if ($icon):?>
							<?php echo YsaHtml::link($entry->image('icon') . '<br/>iPad Icon', array('application/download/id/' . $entry->id . '/image/icon'), array('class' => 'btn w_60')); ?>
						<?php else: ?>
							<div class="errorMessage">No iOS uploaded</div>
						<?php endif?>
					</section>
					<section class="g2 c">
						<?php if ($splash):?>
							<?php echo YsaHtml::link($entry->image('splash_bg_image') . '<br/>Splash Image', array('application/download/id/' . $entry->id . '/image/splash_bg_image'), array('class' => 'btn w_60')); ?>
						<?php else: ?>
							<div class="errorMessage">No Splash uploaded</div>
						<?php endif?>
					</section>
					<div class="clearfix"></div>
				</div>
			</section>
		</fieldset>
		
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

		
		<?php $this->endWidget(); ?>
		
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
		
		
		$('#application-preview-link').fancybox({
			type:'ajax',
			href:_admin_url + 'application/preview/id/<?php echo $entry->id?>',
			onComplete:function(){
				$('#ipad-slider').slides();
			}
		})
		
		
		
//		$('#application-preview-link').click(function(e){
//			e.preventDefault();
//			var link = $(this);
//			
//			$.post(_admin_url + '/application/preview/id/' + link.data('id'))
//			
//		})
//		
	});
</script>