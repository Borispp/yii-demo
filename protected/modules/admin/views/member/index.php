<div class="g12">


	<div>

		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'method' => 'get',
		)); ?>
		
		<fieldset>
			<div class="g12"><h3 id="search_toggler">Search</h3></div>
			<div class="clearfix"></div>
			<div id="search_form" class="<?php echo !isset($_GET['YsaMemeberSearchForm']) ? 'hidden' : '' ?>">
			<fieldset>
			<section class="search-form">
				<?php echo $form->label($member_search,'keywords'); ?>
				<div>
					<?php echo $form->textField($member_search,'keywords'); ?>
				</div>
			</section>
			<section class="search-form">
				<?php echo $form->label($member_search,'order_by'); ?>
				<div>
					<?php echo $form->dropDownList($member_search, 'order_by', $member_search->orderByOptions()); ?>
				</div>
			</section>
			<section class="search-form">
				<?php echo $form->label($member_search,'order_sort'); ?>
				<div>
					<?php echo $form->dropDownList($member_search, 'order_sort', $member_search->orderSortOptions()); ?>
				</div>
			</section>
			<section class="search-form">
				<?php echo $form->label($member_search,'state'); ?>
				<div>
					<?php echo $form->dropDownList($member_search, 'state', $member_search->stateOptions()); ?>
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
		<?php echo CHtml::link('Export to CSV', array('member/export'), array('class' => 'btn i_download icon')); ?>
		<?php echo CHtml::link('Add New', array('add'), array('class' => 'btn i_plus icon ysa fr')); ?>
		<?php echo CHtml::link('Send announcement', array('announcement/addtoall'), array('class' => 'btn i_mail icon ysa fr')); ?>
		<span class="clearfix"></span>
	</p>
	
	<table class="data">
		<thead>
		<tr>
			<th class="w_1"><input type="checkbox" value="" class="ids-toggle" /></th>
			<th class="w_1">ID</th>
			<th class="l">Name</th>
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
				<h4><?php echo CHtml::link($entry->name(), array('view', 'id' => $entry->id)); ?></h4>
				<span><?php echo $entry->email; ?></span>
			</td>
			<td><?php echo $entry->state(); ?></td>
			<td>
				<?php echo CHtml::link('View', array('view', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
				<?php echo CHtml::link('Edit', array('edit', 'id' => $entry->id), array('class' => 'btn small blue')); ?>
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