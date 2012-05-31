<?php
    $this->pageTitle=Yii::app()->name . ' — ' . $title;
    $this->breadcrumbs=array($title);
?>

<?php if(Yii::app()->user->hasFlash('register')): ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('register'); ?>
</div>

<?php else: ?>

<p>
    Введите ваши регистрационные данные.
</p>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'register-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <p class="note">Поля с <span class="required">*</span> обязательны для заполнения.</p>

    <?php  echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'login'); ?>
        <?php echo $form->textField($model,'login'); ?>
        <?php echo $form->error($model,'login'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'pass2'); ?>
        <?php echo $form->passwordField($model,'pass2'); ?>
        <?php echo $form->error($model,'pass2'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'first_name'); ?>
        <?php echo $form->textField($model,'first_name'); ?>
        <?php echo $form->error($model,'first_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'middle_name'); ?>
        <?php echo $form->textField($model,'middle_name'); ?>
        <?php echo $form->error($model,'middle_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'last_name'); ?>
        <?php echo $form->textField($model,'last_name'); ?>
        <?php echo $form->error($model,'last_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'degree'); ?>
        <?php echo $form->textField($model,'degree'); ?>
        <?php echo $form->error($model,'degree'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'phone'); ?>
        <?php echo $form->textField($model,'phone'); ?>
        <?php echo $form->error($model,'phone'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'additional_phone'); ?>
        <?php echo $form->textField($model,'additional_phone'); ?>
        <?php echo $form->error($model,'additional_phone'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email'); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'country'); ?>
        <?php echo $form->textField($model,'country'); ?>
        <?php echo $form->error($model,'country'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'city'); ?>
        <?php echo $form->textField($model,'city'); ?>
        <?php echo $form->error($model,'city'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'address'); ?>
        <?php echo $form->textArea($model,'address'); ?>
        <?php echo $form->error($model,'address'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'position'); ?>
        <?php echo $form->textField($model,'position'); ?>
        <?php echo $form->error($model,'position'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'institution'); ?>
        <?php echo $form->textField($model,'institution'); ?>
        <?php echo $form->error($model,'institution'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'department'); ?>
        <?php echo $form->textField($model,'department'); ?>
        <?php echo $form->error($model,'department'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'may_reviewer'); ?>
        <?php echo $form->checkBox($model,'may_reviewer'); ?>
        <?php echo $form->error($model,'may_reviewer'); ?>
    </div>



    <?php /* if(CCaptcha::checkRequirements()): ?>
    <div class="row">
        <?php echo $form->labelEx($model,'verifyCode'); ?>
        <div>
            <?php $this->widget('CCaptcha'); ?>
            <?php echo $form->textField($model,'verifyCode'); ?>
        </div>
        <div class="hint">Please enter the letters as they are shown in the image above.
            <br/>Letters are not case-sensitive.</div>
        <?php echo $form->error($model,'verifyCode');
    </div>
    <?php endif; */?>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Зарегистрироваться'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif;?>
