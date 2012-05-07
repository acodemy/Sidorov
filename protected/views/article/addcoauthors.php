<div class="form">


    <?php $form=$this->beginWidget('CActiveForm', array(
                                                       'id'=>'coauthors-add-form',
                                                       'enableAjaxValidation'=>false,
                                                  )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php $model= new Coauthor();     ?>
    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'first_name'); ?>
        <?php echo $form->textField($model,'first_name'); ?>
        <?php echo $form->error($model,'first_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'last_name'); ?>
        <?php echo $form->textField($model,'last_name'); ?>
        <?php echo $form->error($model,'last_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'middle_name'); ?>
        <?php echo $form->textField($model,'middle_name'); ?>
        <?php echo $form->error($model,'middle_name'); ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
        <?php
            $url = $this->createUrl('article/addfiles', array('id' => (int)$_GET['id']));
            echo "<a href='{$url}'>Перейти к следующему шагу</a>";
        ?>
    </div>

    <?php $this->endWidget(); ?>

    <?php

    $dataProvider=new CActiveDataProvider('Coauthor', array(
      'criteria'=>array(
          'condition'=>'article_id=' . (int)$_GET['id']
      ),
    ));

    $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
    ));
    ?>

</div><!-- form -->