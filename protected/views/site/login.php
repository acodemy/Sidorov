<?php
    $this->pageTitle=Yii::app()->name . ' — ' . $title;
    $this->breadcrumbs=array($title);
?>

<div style="margin:0 auto;">
<?php $form = $this->beginWidget('CActiveForm', array(
	'enableClientValidation' => true,
	'clientOptions' => array(
		'validateOnSubmit' => true,
	),
    'htmlOptions' => array(
        'class' => 'form',
        'style' => 'width:auto'
    )
));
?>

    <div class="rows">

		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username', array('class' => 'input-medium')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="rows">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password', array('class' => 'input-medium')); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="rows rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
        <?php echo CHtml::submitButton('Войти', array('class' => 'btn')); ?>
	</div>

	<div class="rows buttons">

	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
