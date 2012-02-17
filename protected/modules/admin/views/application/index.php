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
				<th class="w_1">ID</th>
				<th class="l">Name</th>
				<th class="w_5">Status</th>
				<th class="w_20">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($entries as $entry) : ?>
				<tr>
					<td><?php echo $entry->id; ?></td>
					<td class="l">
						<?php echo YsaHtml::link($entry->name, array('edit', 'id' => $entry->id)); ?>
					</td>
					<td class="state <?php echo strtolower($entry->status())?>"><?php echo $entry->status(); ?></td>
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