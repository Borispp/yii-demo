<div class="g12">
    <?php echo $this->renderPartial('/_messages/save');?>

    <div class="form tab">
        <?php $form=$this->beginWidget('YsaAdminForm', array(
			'id'=>'translation-form',
        ));
		?>
		<table class="data">
			<thead>
				<tr>
					<th class="w_1">#</th>
					<th class="w_250p l">Source</th>
					<?php foreach (Yii::app()->params['languages'] as $lang => $title) : ?>
						<th class="l"><?php echo $title; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody id="translation-tbody">
				<?php foreach ($sources as $src) : ?>
					<tr>
						<td><input type="checkbox" class="del" value="<?php echo $src->id; ?>" name="ids[]" /></td>
						<td><input type="text" value="<?php echo $src->message; ?>" name="source[]" class="w_95" /></td>
						<?php foreach (Yii::app()->params['languages'] as $lang => $title) : ?>
							<td class="l">
								<input type="text" value="<?php echo $src->translate($lang); ?>" name="translation[<?php echo $lang?>][]" class="w_95" />
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<div>
			<a href="#" class="btn small red icon i_recycle delete-entries" id="translation-delete-rows">Delete Selected</a>
			<a href="#" id="translation-add-new-row" class="btn small yellow icon i_plus fr">Add new row</a>	
		</div>
		<?php echo YsaHtml::adminSaveSection();?>
		<?php $this->endWidget();; ?>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		
		var tbody = $('#translation-tbody');
		$('#translation-add-new-row').click(function(e){
			e.preventDefault();
			
			var html = '<tr><td>&nbsp;</td>';
			html += '<td class="source"><input type="text" name="source[]" class="w_95" /></td>';
			<?php foreach (Yii::app()->params['languages'] as $lang => $title) : ?>
				html += '<td class="l"><input type="text" value="" name="translation[<?php echo $lang?>][]" class="w_95" /></td>';
			<?php endforeach; ?>
			html += '</tr>';
			tbody.append(html);
		});
	})
</script>