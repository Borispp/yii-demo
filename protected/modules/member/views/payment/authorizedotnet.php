<div class="w" id="subscription-add">
	<section class="box">
		<div class="box-title"><h3>Authorize.net</h3></div>
		<div class="box-content">
			<div class="form standart-form">
				<?php echo YsaHtml::form($prepareUrl)?>
				<section class="cf">
					<?php echo YsaHtml::label('Email', 'email')?>
					<div>
						<?php echo YsaHtml::textField('email', $memberEmail, array('id' => 'email'))?>
					</div>
				</section>
				<section class="cf">
					<?php echo YsaHtml::label('Phone', 'phone')?>
					<div>
						<?php echo YsaHtml::textField('phone', '', array('id' => 'phone'))?>
					</div>
				</section>
				<section class="cf">
					<?php echo YsaHtml::label('Card Number', 'cfull')?>
					<div>
						<?php echo YsaHtml::textField('cfull', '', array('id' => 'cfull'))?>
					</div>
				</section>
				<section class="cf">
					<?php echo YsaHtml::label('Name on the Card', 'full_name')?>
					<div>
						<?php echo YsaHtml::textField('full_name', '', array('id' => 'full_name'))?>
					</div>
				</section>

				<section class="cf">
					<?php echo YsaHtml::label('Valid Month', 'v1')?>
					<div>
						<?php echo YsaHtml::dropDownList('v1', '',
							array(
								1 => '01',
								2 => '02',
								3 => '03',
								4 => '04',
								5 => '05',
								6 => '06',
								7 => '07',
								8 => '08',
								9 => '09',
								10 => '10',
								11 => '11',
								12 => '12'
							), array('id' => 'v1'));?>
					</div>
				</section class="cf">
				<section class="cf">
					<?php echo YsaHtml::label('Valid Year', 'v2')?>
					<div>
						<?php echo YsaHtml::dropDownList('v2', '',
							array('2012' => '2012','2013' => '2013','2014' => '2014','2015' => '2015', '2016' => '2016','2017' => '2017'), array('id' => 'v2'));?>
					</div>
				</section class="cf">

				<div class="button">
					<?php echo YsaHtml::submitButton('Pay', array('class' => 'blue'));?>
				</div>
				<?php echo YsaHtml::endForm()?>
			</div>
			<p><div class="AuthorizeNetSeal" style="padding-left: 250px;"><script type="text/javascript" language="javascript">// <![CDATA[
 var ANS_customer_id="7dc455b1-10bc-495f-942c-a38bc81ec7a3";
// ]]></script><script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js"></script><a id="AuthorizeNetText" href="http://www.authorize.net/" target="_blank">Online Payment Service</a></div></p>
		</div>

	</section>
</div>





			