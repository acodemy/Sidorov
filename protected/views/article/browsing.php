
<?php

$this->pageTitle=Yii::app()->name . ' - Личный кабинет';
$this->breadcrumbs=array(
    'browsing',
);
if(Yii::app()->user->hasFlash('contact')):
?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('contact'); ?>
</div>
<?php else: ?>

<h1>Личный кабинет</h1>

<p>Сейчас вы просматриваете <?php $this->get_name_status($model['status']) ?> статьи</p>

<?php

$dataProvider = $model['dataProvider'];

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'id',

        array(
            'name'=>'title',
            'type'=>'raw',
            'value'=>'CHtml::link(CHtml::encode($data->title), "index.php?r=article/".ArticleController::return_name_status($data->status)."&id=".$data->id)',
        ),
        'description',
        'comment',
        'status',
        'user_id',
        'section_id'


        )
));


echo CHtml::link('Назад', Yii::app()->request->hostInfo.$this->CreateURL(' '));


endif; ?>

