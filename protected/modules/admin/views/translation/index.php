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
					<th class="w_30 l">Source</th>
					<?php foreach (Yii::app()->params['languages'] as $lang => $title) : ?>
						<th class="l"><?php echo $title; ?></th>
					<?php endforeach; ?>
					
				</tr>
			</thead>
		</table>
		
		
		<?php echo YsaHtml::adminSaveSection();?>
		
		<?php $this->endWidget();; ?>
	</div>
</div>