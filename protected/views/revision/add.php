<?php
$this->breadcrumbs=array(
	'Revision'=>array('/revision'),
	'Add',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	Тут идёт описание статьи.
    Тут предлагается скачать файлы к статье.
    Здесь форма добавления рецензии.
</p>
<div class="form">

    <?php

    $form=$this->beginWidget('CActiveForm', array(
        'enableAjaxValidation' => false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    ));

    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'comment'); ?>
        <?php echo $form->textArea($model,'comment'); ?>
        <?php echo $form->error($model,'comment'); ?>
    </div>

    <div class="row">
        <label for='file'>Добавить файл</label>
        <input type='file' name='file' />
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Добавить рецензию'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->