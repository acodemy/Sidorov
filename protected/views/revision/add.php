<?php
$this->breadcrumbs=array(
	'Рецензии'=>array('/revision'),
	'Добавление',
);
if(Yii::app()->user->hasFlash('add')):
?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('add'); ?>
</div>
<?php else: ?>


<div class="rows">
    <dl class="dl-horizontal" style='text-overflow: clip;'>
        <dt><?php echo $revision->article->getAttributeLabel('title');?></dt>
        <dd><?php echo $revision->article->title; ?></dd>

        <dt><?php echo $revision->article->getAttributeLabel('description');?></dt>
        <dd><?php echo $revision->article->description; ?></dd>

        <dt><?php echo $revision->article->getAttributeLabel('section.name');?></dt>
        <dd><?php echo $revision->article->section->name; ?></dd>

        <dt>Авторы</dt>
        <dd><?php echo $revision->article->author->getFullName(); ?></dd>
        <?php
        if ($revision->article->coauthorsCount) {
            $coauthors = $revision->article->coauthors;
            foreach ($coauthors as $coauthor) {
                echo "<dd>{$coauthor->getFullName()}</dd>\n";
            }
        }
        ?>
    </dl>
</div>
    <?php echo CHtml::link('<i class="icon-download-alt"></i> Скачать для просмотра', $revision->article->getArchiveLink(), array('class' => 'btn btn-info')); ?>
    <hr />
<div class="form">

    <?php

    $form=$this->beginWidget('CActiveForm', array(
        'enableAjaxValidation' => false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    ));

    ?>

    <?php echo $form->errorSummary($revision); ?>

    <div class="rows">
        <label for='file'>Добавить файл с рецензией</label>
        <input id='file' type='file' name='file' class='input-file' />
    </div>

    <div class="rows">
        <?php echo $form->labelEx($revision,'comment'); ?>
        <?php echo $form->textArea($revision,'comment', array('class' => 'span6', 'rows' => '4')); ?>
        <?php echo $form->error($revision,'comment'); ?>
    </div>

    <div class="rows buttons">
        <?php echo CHtml::submitButton('Добавить рецензию', array('class' => 'btn btn-primary')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>