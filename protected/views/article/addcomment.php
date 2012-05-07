<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'article-index-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('name' => 'addArticleForm'),
)); ?>




	<p class="note">Fields with <span class="required">*</span> are required.</p>



	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment'); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->