<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('addcoauthors'); ?>
</div>


<div class='form'>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'enableAjaxValidation' => false,
    )); ?>

    <?php
    echo $form->errorSummary($article); ?>

    <div class="rows">
        <dl class="dl-horizontal">
            <dt>Название статьи</dt>
            <dd><?php echo $article->title; ?></dd>
            <dt>Описание статьи</dt>
            <dd><?php echo $article->description; ?></dd>
        </dl>
    </div>
    <div class="rows buttons">
        <?php echo CHtml::submitButton('Подтвердить отправку статьи', array('name' => 'submit', 'class' => 'btn btn-primary')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
