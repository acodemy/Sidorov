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
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <label for='file'>Добавить файл</label>
        <input type='file' name='file' />
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Добавить файл'); ?>
        <?php
            $url = $this->createUrl('article/addcomment', array('id' => $id));
            echo "<a href='{$url}'>Перейти к следующему шагу</a>"; //появляется если хотя бы 1 файл добавлен
        ?>
    </div>

<?php $this->endWidget(); ?>

<?php
    $dataProvider=new CActiveDataProvider('FileArticle', array(
       'criteria'=>array(
           'condition'=>'article_id=' . $id
       ),
    ));

    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'files',
         'dataProvider' => $dataProvider,
         'columns' => array(
             'filename',
             'title',
             array(
                 'class' => 'CButtonColumn',
                 'buttons' => array(
                     'delete' => array(
                         'url' => 'Yii::app()->controller->createUrl("file/delete", array("id" => $data->primaryKey))',
                     ),
                 ),
                 'template' => '{delete}'
             ),
         ),
    ));
?>

</div><!-- form -->

<?php endif; ?>