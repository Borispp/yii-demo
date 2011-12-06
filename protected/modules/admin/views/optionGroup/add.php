<div class="g12">
    <div class="form">
        <?php $form=$this->beginWidget('YsaAdminForm', array(
                'id'=>'option-group-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <fieldset>
            <label>General Information</label>
            
            <section>
                <?php echo $form->labelEx($entry,'slug'); ?>
                <div>
                    <?php echo $form->textField($entry,'slug', array('size'=>60,'maxlength'=>100, 'class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'slug'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'title'); ?>
                <div>
                    <?php echo $form->textField($entry,'title',array('size'=>50,'maxlength'=>50, 'class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'title'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'hidden'); ?>
                <div>
                    <?php echo $form->checkBox($entry,'hidden', array(
                        'checked' => $entry->hidden,
                    )); ?>
                    <?php echo $form->error($entry,'hidden'); ?>
                </div>
            </section>
        </fieldset>
        <?php echo YsaHtml::adminSaveSection();?>
        <?php $this->endWidget(); ?>
    </div>
</div>