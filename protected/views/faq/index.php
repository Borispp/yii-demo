<?php echo YsaHtml::pageHeaderTitle('FAQ'); ?>
<div class="body w">
	<?php foreach($faq as $obFaq):?>
	<h3><?php echo $obFaq->question?></h3>
	<div class="answer"><?php echo $obFaq->question?></div>
	<?php endforeach?>
</div>