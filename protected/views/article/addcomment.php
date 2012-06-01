<?php if(Yii::app()->user->hasFlash('addcoauthors')):
    ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('addcoauthors'); ?>
</div>
<?php else: ?>

<div class="form">

<?php
    $form=$this->beginWidget('CActiveForm', array(
        'enableAjaxValidation' => true,
    ));
?>

<?php echo $form->errorSummary($model); ?>

	<div class="rows">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment', array('class' => 'span6', 'rows' => '4')); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="rows buttons">
		<?php echo CHtml::submitButton('Сохранить и перейти к следующему шагу', array('class' => 'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php endif; ?>