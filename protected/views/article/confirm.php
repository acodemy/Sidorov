<div class='form'>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'enableAjaxValidation' => false,
    )); ?>

    <?php
    echo $form->errorSummary($model); ?>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Отправить статью', array('name' => 'submit')); ?>
    </div>

    <?php $this->endWidget(); ?>


</div><!-- form -->