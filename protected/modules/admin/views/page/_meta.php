<fieldset>
    <section>
        <?php echo $form->labelEx($meta,'title'); ?>
        <div>
            <?php echo $form->textField($meta,'title', array('size'=>60,'maxlength'=>100)); ?>
            <?php echo $form->error($meta,'title'); ?>
        </div>
    </section>
    <section>
        <?php echo $form->labelEx($meta,'keywords'); ?>
        <div>
            <?php echo $form->textArea($meta,'keywords', array(
                'data-autogrow' => 'true',
                'rows'          => 5,
            )); ?>
            <?php echo $form->error($meta,'keywords'); ?>
        </div>
    </section>
    <section>
        <?php echo $form->labelEx($meta,'description'); ?>
        <div>
            <?php echo $form->textArea($meta,'description', array(
                'data-autogrow' => 'true',
                'rows'          => 5,
            )); ?>
            <?php echo $form->error($meta,'description'); ?>
        </div>
    </section>

</fieldset>