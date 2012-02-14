<div class="g12">
	<?php echo $this->renderPartial('/_messages/save');?>
	<div>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'method' => 'get',
		)); ?>
		
		<fieldset>
			<div class="g12"><h3 id="search_toggler">Search</h3></div>
			<div class="clearfix"></div>
			<div id="search_form" class="<?php echo !isset($_GET['YsaTutorialSearchForm']) ? 'hidden' : '' ?>">
			<fieldset>
			<section class="search-form">
				<?php echo $form->label($tutorialSearch,'keywords'); ?>
				<div>
					<?php echo $form->textField($tutorialSearch,'keywords'); ?>
				</div>
			</section>
			<section class="search-form">
				<?php echo $form->label($tutorialSearch,'category'); ?>
				<div>
					<?php echo $form->dropDownList($tutorialSearch, 'category', $tutorialSearch->categories()); ?>
				</div>
			</section>
				
			<section class="search-form">
				<?php echo $form->label($tutorialSearch,'order_by'); ?>
				<div>
					<?php echo $form->dropDownList($tutorialSearch, 'order_by', $tutorialSearch->orderByOptions()); ?>
				</div>
			</section>
			<section class="search-form">
				<?php echo $form->label($tutorialSearch,'order_sort'); ?>
				<div>
					<?php echo $form->dropDownList($tutorialSearch, 'order_sort', $tutorialSearch->orderSortOptions()); ?>
				</div>
			</section>
			<section class="search-form">
				<?php echo $form->label($tutorialSearch,'state'); ?>
				<div>
					<?php echo $form->dropDownList($tutorialSearch, 'state', $tutorialSearch->stateOptions()); ?>
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
		'id'=>'member-form',
		'action'=>Yii::app()->createUrl('/admin/member/delete'),
		'method'=>'post',
	)); ?>

	<p>
		<?php echo YsaHtml::link('Add New', array('add'), array('class' => 'btn i_plus icon ysa fr')); ?>
		<span class="clearfix"></span>
	</p>
	
	<table class="data">
		<thead>
		<tr>
			<th class="w_1"><input type="checkbox" value="" class="ids-toggle" /></th>
			<th class="w_1">ID</th>
			<th class="l">Title</th>
			<th class="w_20 l">Category</th>
			<th class="w_5">Status</th>
			<th class="w_10">&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($entries as $entry) : ?>
		<tr>
			<td><input type="checkbox" class="del" value="<?php echo $entry->id; ?>" name="ids[]" /></td>
			<td><?php echo $entry->id; ?></td>
			<td class="l">
				<h4><?php echo YsaHtml::link($entry->title, array('edit', 'id' => $entry->id)); ?></h4>
			</td>
			<td class="l"><strong><?php echo $entry->category->name; ?></strong></td>
			<td><?php echo $entry->state(); ?></td>
			<td>
				<?php echo YsaHtml::link('Edit', array('edit', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
			</td>
		</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<p class="fl"><button class="submit small red icon i_recycle delete-entries">Delete Selected</button></p>
	<?php $this->widget('YsaAdminPager',array('pages'=>$pagination)) ?>
	<div class="clearfix"></div>
	<?php $this->endWidget(); ?>
</div>