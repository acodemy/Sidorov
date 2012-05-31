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

    <p class="note">Поля, отмеченные звёздочкой <span class="required">*</span> являются обязательными.</p>

<?php echo $form->errorSummary($model); ?>

    <div class="rows">
        <?php echo $form->labelEx($model,'first_name'); ?>
        <?php echo $form->textField($model,'first_name'); ?>
        <?php echo $form->error($model,'first_name'); ?>
    </div>

    <div class="rows">
        <?php echo $form->labelEx($model,'middle_name'); ?>
        <?php echo $form->textField($model,'middle_name'); ?>
        <?php echo $form->error($model,'middle_name'); ?>
    </div>

    <div class="rows">
        <?php echo $form->labelEx($model,'last_name'); ?>
        <?php echo $form->textField($model,'last_name'); ?>
        <?php echo $form->error($model,'last_name'); ?>
    </div>


    <div class="rows buttons">
        <?php echo CHtml::submitButton('Добавить соавтора', array('class' => 'btn btn-primary')); ?>
        <?php
            $url = $this->createUrl('article/addfiles', array('id' => (int)$_GET['id']));
            echo "<a class='btn' href='{$url}'>Перейти к следующему шагу</a>";
        ?>
    </div>

<?php $this->endWidget(); ?>

<?php

$dataProvider = new CActiveDataProvider('Coauthor', array(
  'criteria'=>array(
      'condition'=>'article_id=' . $id // Передаётся из контроллера
  ),
));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'coauthors',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'first_name',
        'middle_name',
        'last_name',
        array(
            'class' => 'CButtonColumn',
            'buttons' => array(
                'delete' => array(
                    'url' => 'Yii::app()->controller->createUrl("coauthor/delete", array("id" => $data->primaryKey))',
                ),
            ),
            'template' => '{delete}'
        ),
    ),
    'htmlOptions' => array(
        'class' => 'table table-striped'
    ),
));
?>

</div><!-- form -->
<?php endif; ?>