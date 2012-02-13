<div class="w" id="subscription-add">
	<section class="box">
		<div class="box-title"><h3>Authorize.net</h3></div>
		<div class="box-content">
			<div class="form standart-form">

				<?php $form=$this->beginWidget('YsaMemberForm', array(
					'id'=>'authorizedotnet-form',
					'action' => $formAction
				)); ?>
				<section class="cf">
					<?php echo $form->labelEx($entry,'full_name'); ?>
					<div>
						<?php echo $form->textField($entry,'full_name', array('maxlength' => 100)); ?>
						<?php echo $form->error($entry,'full_name'); ?>
					</div>
				</section>

				<section class="cf">
					<?php echo $form->labelEx($entry,'email'); ?>
					<div>
						<?php echo $form->textField($entry,'email', array('maxlength' => 100)); ?>
						<?php echo $form->error($entry,'email'); ?>
					</div>
				</section>

				<section class="cf">
					<?php echo $form->labelEx($entry,'phone'); ?>
					<div>
						<?php echo $form->textField($entry,'phone', array('maxlength' => 100)); ?>
						<?php echo $form->error($entry,'phone'); ?>
					</div>
				</section>

				<section class="cf">
					<?php echo $form->labelEx($entry,'card_number'); ?>
					<div>
						<?php echo $form->textField($entry,'card_number', array('maxlength' => 100)); ?>
						<?php echo $form->error($entry,'card_number'); ?>
					</div>
				</section>

				<section class="cf">
					<?php echo $form->labelEx($entry,'expire_month'); ?>
					<div>
						<?php echo $form->dropDownList($entry, 'expire_month', $entry->getMonths()); ?>
						<?php echo $form->error($entry,'expire_month'); ?>
					</div>
				</section class="cf">
				<section class="cf">
					<?php echo $form->labelEx($entry,'expire_year'); ?>
					<div>
						<?php echo $form->dropDownList($entry, 'expire_year', $entry->getYears()); ?>
						<?php echo $form->error($entry,'expire_year'); ?>
					</div>
				</section class="cf">

				<div class="button">
					<?php echo YsaHtml::submitButton('Pay', array('class' => 'blue')); ?>
				</div>
				<?php if ($errorMessage):?>
					<div class="errorMessage"><?php echo $errorMessage?></div>
				<?php endif;?>
				<?php $this->endWidget(); ?>
			</div>
			<p><div class="AuthorizeNetSeal" style="padding-left: 250px;"><script type="text/javascript" language="javascript">// <![CDATA[
		var ANS_customer_id="7dc455b1-10bc-495f-942c-a38bc81ec7a3";
		// ]]></script><script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js"></script><a id="AuthorizeNetText" href="http://www.authorize.net/" target="_blank">Online Payment Service</a></div></p>
		</div>

	</section>
</div>