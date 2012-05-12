<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'enableAjaxValidation' => false,
)); ?>

<p class="note">Поля, отмеченные звёздочкой <span class="required">*</span> являются обязательными.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textArea($model,'title'); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description'); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'section_id'); ?>
        <?php echo  $form->dropDownList($model, 'section_id', Section::model()->showAll()); ?>
        <?php echo $form->error($model,'section_id'); ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Добавить статью и перейти к следующему шагу'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->