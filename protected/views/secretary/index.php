<?php
$this->breadcrumbs=array(
	'Secretary'=>array('/secretary'),
	'Authors',
);?>

<?php if(Yii::app()->user->hasFlash('index')):
    ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('index'); ?>
</div>
<?php else: ?>
<p>
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$dataProvider,
        'columns'=>array(
            array(

                'name' => 'title',
                'type'=>'raw',
                 'value'=>'CHtml::link(CHtml::encode($data->title), "index.php?r=secretary/article"."&id=".$data->id)',
                ),
            array(

                'name' => 'author',
                'type'=>'raw',
                'value'=>'User::model()->findByPk($data->user_id)->login',
            ),
            'description',
            'comment',
            'section_id',
            array(
                'name' => 'Status',
                'type'=>'raw',
                'value'=>'Article::getNameStatus($data->status)'
            ),
        ),
        'htmlOptions' => array(
            'class' => 'table table-striped'
        ),
        'pagerCssClass' => 'pager'
    ));
    ?>
</p>

    <?php endif; ?>
