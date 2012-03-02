<?php $form=$this->beginWidget('YsaAdminForm', array(
		'id'	  =>'tutorial-form',
		'htmlOptions'=>array('enctype' => 'multipart/form-data'),
)); ?>

	<fieldset>
		<section>
			<?php echo $form->labelEx($entry,'title'); ?>
			<div>
				<?php echo $form->textField($entry,'title', array('size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($entry,'title'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($entry,'slug'); ?>
			<div>
				<?php echo $form->textField($entry,'slug', array('size'=>60,'maxlength'=>50)); ?>
				<?php echo $form->error($entry,'slug'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($entry,'cat_id'); ?>
			<div>
				<?php echo $form->dropDownList($entry,'cat_id', YsaHtml::listData($entry->getCategories(), 'id' , 'name')); ?>
				<?php echo $form->error($entry,'cat_id'); ?>
			</div>
		</section>

		<section>
			<?php echo $form->labelEx($entry,'state'); ?>
			<div>
				<?php echo $form->dropDownList($entry, 'state', $entry->getStates()); ?>
				<?php echo $form->error($entry,'state'); ?>
			</div>
		</section>
		
		<section>
			<?php echo $form->labelEx($entry,'preview'); ?>
			<div>

				<?php if ($entry->preview) : ?>
					<div class="image">
						<?php echo $entry->preview(); ?>
							<?php echo YsaHtml::link('Delete', array('tutorial/deleteImage/id/' . $entry->id), array('class' => 'btn small red')); ?>
					</div>
				<?php else:?>
					<?php echo $form->fileField($entry,'preview'); ?>
					<?php echo $form->error($entry,'preview'); ?>
				<?php endif; ?>
			</div>
		</section>
		
		<section>
			<?php echo $form->labelEx($entry,'video'); ?>
			<div>
				<?php echo $form->textField($entry,'video', array('size'=>60,'maxlength'=>255)); ?>
				<?php echo $form->error($entry,'video'); ?>
				<?php if ($entry->video) : ?>
					<div class="video"><?php echo $entry->video(); ?></div>
				<?php endif; ?>
			</div>
		</section>
		
		<section>
			<?php echo $form->labelEx($entry,'content'); ?>
			<div>
				<?php echo $form->textArea($entry,'content', array(
					'class' => 'html',
					'rows'  => 5,
				)); ?>
				<?php echo $form->error($entry,'content'); ?>
			</div>
		</section>
		
		<section>
			<label>Files</label>
			<div>
				<div id="tutorial-files">
					<?php foreach ($entry->files as $file) : ?>
						<p class="clearfix">
							<input type="text" name=uploadedfile[<?php echo $file->id?>] class="w_30" value="<?php echo $file->name; ?>" />
							<?php echo YsaHtml::link('Download', $file->url(), array('class' => 'btn small ysa download', 'data-id' => $file->id)); ?>
							|
							<?php echo YsaHtml::link('Delete', '#', array('class' => 'btn small red delete', 'data-id' => $file->id)); ?>
						</p>
					<?php endforeach; ?>
				</div>
				<p class="r">
					<?php echo YsaHtml::link('Add New File', '#', array('id' => 'tutorial-file-add', 'class' => 'btn green small')); ?>
				</p>
			</div>
		</section>
		
	</fieldset>
	<?php echo YsaHtml::adminSaveSection();?>
<?php $this->endWidget(); ?>


<script type="text/javascript">
	$(function(){
		
		var html = '<p class="clearfix">';
			html+= '<input type="text" name="file[]" class="w_30" />';
			html+= '<input type="file" name="file[]" />';
			html+= '</p>';
		
		var tutorial_files = $('#tutorial-files');
		$('#tutorial-file-add').click(function(e){
			e.preventDefault();
			var _html = $(html);
			tutorial_files.append(_html);
			
			_html.find('input[type=file]').uniform();
		});
		
		
		tutorial_files.find('a.delete').click(function(e){
			e.preventDefault();
			var link = $(this);
			
			$.confirm('Are you sure?', function(){
				$.post(_admin_url + '/tutorial/deleteFile', {
					id:link.data('id')
				}, function(data){
					if (data.success) {
						link.parent().fadeOut('fast', function(){$(this).remove()});
					}
				}, 'json');
			})
		});
	});
</script>