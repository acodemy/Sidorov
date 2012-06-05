<?php
$this->breadcrumbs=array(
    'Secretary'=>array('/secretary'),
    'Revisions',
);
if(Yii::app()->user->hasFlash('revision')):
    ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('revision'); ?>
</div>
<?php else: ?>
<p>
    <?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        array(

            'name' => 'title',
            'type'=>'raw',
            'value'=>'CHtml::link(CHtml::encode(Article::model()->findByPk($data->article_id)->title), "index.php?r=secretary/article"."&id=".$data->id)',
        ),
        array(

            'name' => 'author',
            'type'=>'raw',
            'value'=>'User::model()->findByPk($data->user_id)->login',
        ),
        'comment',
         array(
            'name' => 'Status',
            'type'=>'raw',
            'value'=>'Revision::getNameStatus($data->status)'
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
