<div class="g12">
    <?php echo $this->renderPartial('/_messages/save');?>

    <div class="form tab">
        <?php $form=$this->beginWidget('YsaAdminForm', array(
                'id'=>'member-form',
                'enableAjaxValidation'=>false,
        )); ?>
        
        <ul>
            <li><a href="#tab-general">General</a></li>
            <li><a href="#tab-meta">Meta</a></li>
			<li><a href="#tab-custom">Custom Fields</a></li>
        </ul>
        
        <div id="tab-general">
			<?php $this->renderPartial('_form', array(
				'entry' => $entry,
				'form'	=> $form,
			)); ?>
        </div>
        
        <div id="tab-meta">
            <?php $this->renderPartial('_meta', array(
                'meta' => $entry->meta(),
                'form' => $form,
            )); ?>
        </div>
		
		<div id="tab-custom">
            <?php $this->renderPartial('_custom', array(
				'entry' => $entry,
                'form'  => $form,
            )); ?>
		</div>
        
        <?php echo YsaHtml::adminSaveSection();?>
        <?php $this->endWidget(); ?>
    </div>
</div>