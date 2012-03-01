<?php $this->renderPartial('member.views.application.ipad-font-style', array('application' => $app));?>
<div id="application-preview">
	<div class="preview" id="application-preview-ipad">
		<div class="ipad ipad900" id="ipad-slider">
			<div class="wrap" id="style-<?php echo $app->option('style')?>">
				<div class="content">
					<ul class="slides_container">
						<?php foreach(array('events','main','studio','gallery') as $template):?>
						<li class="slide" id="slide-<?php echo $template?>"><?php $this->renderPartial('member.views.application.ipad-'.$template, array('application' => $app))?></li>
						<?php endforeach?>
					</ul>
				</div>
			</div>
			<div class="home"><div><span></span></div></div>
		</div>
	</div>
</div>