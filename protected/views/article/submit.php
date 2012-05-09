<div class="form">

<?php
if(Yii::app()->user->hasFlash('contact')):
    ?>

    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('contact'); ?>
    </div>
    <?php else:
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'article-index-form',
        'enableAjaxValidation'=>false,
        'htmlOptions' => array('name' => 'addArticleForm'),
    ));
    ?>




	<p class="note">Fields with <span class="required">*</span> are required.</p>



	<?php echo $form->errorSummary($model['Article']); ?>

    <div class="row">

        <?php echo $form->hiddenField($model['Article'],'id', array('value' => $model['id'])); ?>
        <?php echo $form->error($model['Article'],'id'); ?>
    </div>

    <div class="row">
		<?php echo $form->labelEx($model['Article'],'title'); ?>
		<?php echo $form->textArea($model['Article'],'title'); ?>
		<?php echo $form->error($model['Article'],'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model['Article'],'description'); ?>
		<?php echo $form->textArea($model['Article'],'description'); ?>
		<?php echo $form->error($model['Article'],'description'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model['Article'],'section_id'); ?>
        <?php echo  $form->dropDownList($model['Article'], 'section_id', Section::model()->showAll()); ?>
        <?php echo $form->error($model['Article'],'section_id'); ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget();

    ?>

</div><!-- form -->
<?php
if($model['dataProvider'] != null);
{
    $dataProvider = $model['dataProvider'];

    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider
        )
    );
}
endif; ?>