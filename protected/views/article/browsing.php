<?php
    $this->pageTitle=Yii::app()->name . ' - Личный кабинет';
    $this->breadcrumbs=array(
        'browsing',
    );
    if (Yii::app()->user->hasFlash('browsing')):
?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('browsing'); ?>
</div>
<?php else: ?>

<h1>Личный кабинет</h1>

<p>Сейчас вы просматриваете <?php Article::getNameStatus($model['status']) ?> статьи</p>

<?php

    $dataProvider = $model['dataProvider'];

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$dataProvider,
        'columns'=>array(
            'id',
            array(
                'name'=>'title',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->title), "index.php?r=article/".Article::returnNameStatus($data->status)."&id=".$data->id)',
            ),
            'description',
            'section.name',
        ),
        'htmlOptions' => array(
            'class' => 'table table-striped'
        ),
    ));

    echo CHtml::link('Назад', Yii::app()->request->hostInfo.$this->CreateURL('main'));


endif; ?>

