<?php echo YsaHtml::pageHeaderTitle('FAQ'); ?>
<div class="body w" id="faq">
	<ul class="list">
		<?php foreach($faq as $obFaq):?>
			<li>
				<h3><?php echo $obFaq->question?></h3>
				<div class="answer"><?php echo $obFaq->answer?></div>
			</li>
		<?php endforeach?>
	</ul>
</div>