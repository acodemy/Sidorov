<div class="form">

<?php
    $form=$this->beginWidget('CActiveForm', array(
	'enableAjaxValidation' => false,
));

    ?>
<p class="note">Поля, отмеченные <span class="required">*</span> являются обязательными для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="rows">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textArea($model,'title', array('class' => 'span6', 'rows' => '2')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="rows">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description', array('class' => 'span6', 'rows' => '4')); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

    <div class="rows">
        <?php echo $form->labelEx($model,'section_id'); ?>
        <?php echo  $form->dropDownList($model, 'section_id', Section::model()->showAll()); ?>
        <?php echo $form->error($model,'section_id'); ?>
    </div>

	<div class="rows buttons">
		<?php echo CHtml::submitButton('Сохранить и перейти к следующему шагу', array('class' => 'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->