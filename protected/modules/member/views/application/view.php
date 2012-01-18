<div class="w">

	<section class="box">
		<div class="box-title">
			<h3>Your Application</h3>
		</div>
		<div class="box-content">
			<?php echo YsaHtml::link('Settings Wizard', array('wizard'), array('class' => 'btn')); ?>
			<?php echo YsaHtml::link('Preview Settings', array('settings'), array('class' => 'btn')); ?>
		</div>
	</section>

	
	<section class="box">
		<div class="box-title">
			<h3>Some app tests</h3>
		</div>
		<div class="box-content">
			
			<h3>Notifications</h3>

			<div class="flash">Test Flash</div>

			<div class="flash success">Success Flash</div>

			<div class="flash error">Error Flash</div>

			<div class="flash notice">Notice Flash</div>


			<h3>Buttons test</h3>

			<p>
				<a href="#" class="btn">Simple button</a> <a href="#" class="btn blue">Blue button</a>
			</p>

			<p>
				<a href="#" class="btn small">Small button</a> <a href="#" class="btn blue small">Small button</a>
			</p>

			<p>
				<a href="#" class="btn big">Big button</a> <a href="#" class="btn blue big">Big button</a>
			</p>


			<p>
				<input type="submit" value="Submit" />

				<input type="button" value="Button" />

				<button type="button" class="btn">Button Tag</button>
			</p>

			<p>
				<input type="submit" class="blue" value="Submit" />

				<input type="button" class="blue" value="Button" />

				<button type="button" class="blue">Button</button>
			</p>

			<p>
				<input type="submit" class=" small" value="Submit" />

				<input type="button" class=" small" value="Button" />

				<button type="button" class=" small">Button</button>
			</p>

			<p>
				<input type="submit" class=" big" value="Submit" />

				<input type="button" class=" big" value="Button" />

				<button type="button" class="blue big">Button</button>
			</p>
			
		</div>
	</section>
	
</div>