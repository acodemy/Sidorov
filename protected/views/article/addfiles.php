<?php
if(Yii::app()->user->hasFlash('contact')):
    ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('contact'); ?>
</div>
<?php else:  ?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
                                                       'id'=>'file-article-files-form',
                                                       'enableAjaxValidation'=>false,
                                                       'htmlOptions'=>array('enctype'=>'multipart/form-data'),
                                                  )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php
        $model = new FileArticle();

    echo $form->errorSummary($model); ?>

    <div class="row">
        <input type='file' name='file' />
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Добавить файл'); ?>
        <?php
        $url = $this->createUrl('article/addcomment', array('id' => (int)$_GET['id']));
        echo "<a href='{$url}'>Перейти к следующему шагу</a>"; //появляется если хотя бы 1 файл добавлен
        ?>
    </div>

    <?php $this->endWidget(); ?>

    <?php

    $dataProvider=new CActiveDataProvider('FileArticle', array(
                                                           'criteria'=>array(
                                                               'condition'=>'article_id=' . (int)$_GET['id']
                                                           ),
                                                      ));

    $this->widget('zii.widgets.grid.CGridView', array(
                                                     'dataProvider'=>$dataProvider,
                                                ));
    ?>

</div><!-- form -->
    <?php endif; ?>