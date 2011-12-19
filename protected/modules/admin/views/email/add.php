<div class="g12">
    <div class="form">
        <?php $form=$this->beginWidget('YsaAdminForm', array(
                'id'=>'option-group-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <fieldset>
            <label>General Information</label>
            <section>
                <?php echo $form->labelEx($entry,'name'); ?>
                <div>
                    <?php echo $form->textField($entry,'name', array('size'=>60,'maxlength'=>50, 'class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'name'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'subject'); ?>
                <div>
                    <?php echo $form->textField($entry,'subject',array('size'=>50,'maxlength'=>255, 'class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'subject'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'body'); ?>
                <div>
                    <?php echo $form->textArea($entry,'body', array(
                        'data-autogrow' => 'true',
                        'rows'  => 12,
                    )); ?>
                    <span>HTML is enabled.</span>
                    <?php echo $form->error($entry,'body'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'alt_body'); ?>
                <div>
                    <?php echo $form->textArea($entry,'alt_body', array(
                        'data-autogrow' => 'true',
                        'rows'          => 5,
                    )); ?>
                    <span>No HTML</span>
                    <?php echo $form->error($entry,'alt_body'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'help'); ?>
                <div>
                    <?php echo $form->textArea($entry,'help', array(
                        'data-autogrow' => 'true',
                        'rows'          => 3,
                    )); ?>
                    <?php echo $form->error($entry,'help'); ?>
                </div>
            </section>
        </fieldset>
        <?php echo YsaHtml::adminSaveSection();?>
        <?php $this->endWidget(); ?>
    </div>
</div>