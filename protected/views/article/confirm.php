<div class='form'>

    <?php $form=$this->beginWidget('CActiveForm', array(
                                                       'id'=>'file-article-submit-form',
                                                       'enableAjaxValidation'=>false,
                                                  )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php
    $model = new Article();

    echo $form->errorSummary($model); ?>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Отправить статью'); ?>
    </div>

    <?php $this->endWidget(); ?>


</div><!-- form -->