<div class="g12">
    <div class="form">
        <?php $form=$this->beginWidget('YsaAdminForm', array(
                'id'                   => 'member-form',
                'enableAjaxValidation' => false,
        )); ?>
        <fieldset>
            <label>General Information</label>
            
            <section>
                <?php echo $form->labelEx($entry,'email'); ?>
                <div>
                    <?php echo $form->textField($entry,'email', array('size'=>60,'maxlength'=>100, 'class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'email'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'password'); ?>
                <div>
                    <?php echo $form->textField($entry,'password', array('size'=>60,'maxlength'=>100, 'class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'password'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'first_name'); ?>
                <div>
                    <?php echo $form->textField($entry,'first_name',array('size'=>50,'maxlength'=>50, 'class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'first_name'); ?>
                </div>
            </section>
            
            <section>
                <?php echo $form->labelEx($entry,'last_name'); ?>
                <div>
                    <?php echo $form->textField($entry,'last_name',array('size'=>50,'maxlength'=>50, 'class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'last_name'); ?>
                </div>
            </section>

            <section>
                <?php echo $form->labelEx($entry,'state'); ?>
                <div>
                    <?php echo $form->dropDownList($entry, 'state', $entry->getStates(), array('class' => 'w_50')); ?>
                    <?php echo $form->error($entry,'state', array('class' => 'red')); ?>
                </div>
            </section>
        </fieldset>
        <?php echo YsaHtml::adminSaveSection();?>
        <?php $this->endWidget(); ?>
    </div>
</div>