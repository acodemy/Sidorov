<?php if(Yii::app()->user->hasFlash('addcoauthors')):
?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('addcoauthors'); ?>
</div>
<?php else: ?>
<div class="form">

<?php

    $this->widget('StatusBar', array('status' => $status, 'id' => $id));

    $form=$this->beginWidget('CActiveForm', array(
   'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Поля, отмеченные звёздочкой <span class="required">*</span> являются обязательными.</p>

<?php echo $form->errorSummary($model); ?>

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


    <div class="row buttons">
        <?php echo CHtml::submitButton('Добавить соавтора'); ?>
        <?php
            $url = $this->createUrl('article/addfiles', array('id' => (int)$_GET['id']));
            echo "<a href='{$url}'>Перейти к следующему шагу</a>";
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
));
?>

</div><!-- form -->
<?php endif; ?>