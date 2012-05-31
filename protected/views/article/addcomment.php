<?php if(Yii::app()->user->hasFlash('addcoauthors')):
    ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('addcoauthors'); ?>
</div>
<?php else: ?>

<div class="form">

<?php
    $form=$this->beginWidget('CActiveForm', array(
	'enableAjaxValidation'=>false,
)); ?>

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
<?php endif; ?>