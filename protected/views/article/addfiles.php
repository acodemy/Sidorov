<?php if(Yii::app()->user->hasFlash('addcoauthors')):
    ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('addcoauthors'); ?>
</div>
<?php else: ?>

<div class="form">

<?php
    $form = $this->beginWidget('CActiveForm', array(
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));
?>

<?php echo $form->errorSummary($model); ?>

    <div class="rows">
        <label for='file'>Добавить файл</label>
        <input id='file' type='file' name='file' />
    </div>

    <div class="rows buttons">
        <?php echo CHtml::submitButton('Добавить файл', array('class' => 'btn btn-primary')); ?>
        <?php
            $url = $this->createUrl('article/addcomment', array('id' => $article->id));
            if ($article->filesCount)
                echo "<a href='{$url}' class='btn'>Перейти к следующему шагу</a>"; //появляется если хотя бы 1 файл добавлен
        ?>
    </div>

<?php $this->endWidget(); ?>

<?php
    $dataProvider=new CActiveDataProvider('FileArticle', array(
       'criteria'=>array(
           'condition'=>'article_id=' . $article->id
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
        'htmlOptions' => array(
            'class' => 'table table-striped'
        ),
    ));
?>

</div><!-- form -->

<?php endif; ?>