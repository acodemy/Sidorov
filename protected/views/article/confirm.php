<?php if(Yii::app()->user->hasFlash('addcoauthors')):
    ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('addcoauthors'); ?>
</div>
<?php else:
    $this->widget('StatusBar', array('status' => $model['status'], 'id' => $model['id']));
    ?>

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

<?php endif; ?>