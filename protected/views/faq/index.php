<div id="faq" class="general-page cf">
	<div class="content">
		<div class="questions">
			<ul>
				<?php foreach($faq as $entry):?>
					<li>
						<?php echo YsaHtml::link($entry->question, '#answer-' . $entry->id, array('data-id' => $entry->id)); ?>
					</li>
				<?php endforeach?>
			</ul>
		</div>

		<div class="answers">
			<?php foreach($faq as $entry):?>
				<div class="answer" id="faq-answer-<?php echo $entry->id;?>">
					<a name="answer-<?php echo $entry->id;?>"></a>
					<h3><?php echo $entry->question; ?></h3>
					<div class="page">
						<?php echo $entry->answer?>
					</div>
				</div>
			<?php endforeach?>
		</div>
	</div>
</div>