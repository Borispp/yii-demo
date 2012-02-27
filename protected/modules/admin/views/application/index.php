<div class="g12">
	<div>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'method' => 'get',
		)); ?>
		<fieldset>
			<div class="g12"><h3 id="search_toggler">Search</h3></div>
			<div class="clearfix"></div>
			<div id="search_form" class="<?php echo !isset($_GET['YsaApplicationSearchForm']) ? 'hidden' : '' ?>">
			<fieldset>
			<section class="search-form">
				<?php echo $form->label($app_search,'keywords'); ?>
				<div>
					<?php echo $form->textField($app_search,'keywords'); ?>
				</div>
			</section>
			<section class="search-form">
				<?php echo $form->label($app_search,'order_by'); ?>
				<div>
					<?php echo $form->dropDownList($app_search, 'order_by', $app_search->orderByOptions()); ?>
				</div>
			</section>
			<section class="search-form">
				<?php echo $form->label($app_search,'order_sort'); ?>
				<div>
					<?php echo $form->dropDownList($app_search, 'order_sort', $app_search->orderSortOptions()); ?>
				</div>
			</section>
			<section class="search-form">
				<?php echo $form->label($app_search,'state'); ?>
				<div>
					<?php echo $form->dropDownList($app_search, 'state', $app_search->stateOptions()); ?>
				</div>
			</section>
			<section class="search-form">
				<?php echo $form->label($app_search,'filled'); ?>
				<div>
					<?php echo $form->dropDownList($app_search, 'filled', $app_search->filledOptions()); ?>
				</div>
			</section>
			<section class="search-form">
				<?php echo $form->label($app_search,'paid'); ?>
				<div>
					<?php echo $form->dropDownList($app_search, 'paid', $app_search->paidOptions()); ?>
				</div>
			</section>
			<section class="search-form">
				<div>
					<button class="submit blue">Search</button>
					<button class="submit red" id="reset">Reset</button>
				</div>
			</section>
			</fieldset>
			</div>
		</fieldset>	
		<?php $this->endWidget(); ?>
	</div>
	<?php $this->beginWidget('YsaAdminForm', array(
		'id'=>'application-form',
		'action'=>Yii::app()->createUrl('/admin/application/delete'),
		'method'=>'post',
	)); ?>
	<table class="data">
		<thead>
			<tr>
				<th class="w_1">&nbsp;</th>
				<th class="l">Name</th>
				<th class="w_5">Filled</th>
				<th class="w_5">Paid</th>
				<th class="w_5">Submitted</th>
				<th class="w_5">Approved</th>
				<th class="w_5">Locked</th>
				<th class="w_5">AppStore</th>
				<th class="w_15">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($entries as $entry) : ?>
				<tr>
					<td>
						<?php echo YsaHtml::link($entry->image('itunes_logo', 50, 50), array('application/moderate', 'id' => $entry->id)); ?>
					</td>
					<td class="l">
						<h5><?php echo YsaHtml::link($entry->name, array('moderate', 'id' => $entry->id)); ?></h5>
						<div>
							<code><?php echo $entry->appkey; ?></code>
						</div>
					</td>
					<td class="<?php echo $entry->filled() ? 'true' : 'false'?>">
						<?php echo $entry->filled() ? '<strong>Yes</strong>' : 'No'; ?>
					</td>
					<td class="<?php echo $entry->isPaid() ? 'true' : 'false'?>">
						<?php echo $entry->isPaid() ? '<strong>Yes</strong>' : 'No'; ?>
					</td>
					<td class="<?php echo $entry->submitted() ? 'true' : 'false'?>">
						<?php echo $entry->submitted() ? '<strong>Yes</strong>' : 'No'; ?>
					</td>
					<td class="<?php echo $entry->approved() ? 'true' : 'false'?>">
						<?php echo $entry->approved() ? '<strong>Yes</strong>' : 'No'; ?>
					</td>
					<td class="<?php echo $entry->locked() ? 'true' : 'false'?>">
						<?php echo $entry->locked() ? '<strong>Yes</strong>' : 'No'; ?>
					</td>
					<td class="<?php echo $entry->rejected() ? 'false' : ($entry->running() ? 'true' : ($entry->isReady() ? 'app-ready' : 'none'))?>">
						<?php if ($entry->rejected()) : ?>
							Rejected
						<?php elseif($entry->running()):?>
							Running
						<?php elseif($entry->isReady()):?>
							<strong>Sent</strong>
						<?php else:?>
							&mdash;
						<?php endif; ?>
					</td>
					<td>
						<?php echo YsaHtml::link('Moderate', array('moderate', 'id' => $entry->id), array('class' => 'btn small green')); ?>
						<?php echo YsaHtml::link('Edit', array('edit', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
	<div class="clearfix"></div>
	<?php $this->endWidget(); ?>
</div>