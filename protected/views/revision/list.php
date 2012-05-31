<?php
$this->breadcrumbs=array(
    'Revision'=>array('/revision'),
    'Add',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<?php
$criteria = new CDbCriteria();


$criteria->compare('t.user_id', Yii::app()->user->id);
$criteria->with = array(
    'article',
    'user',
);

$dataProvider = new CActiveDataProvider('Revision', array(
    'criteria' => $criteria
));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'revisions',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'class'=>'CLinkColumn',
            'header'=>'Статья',
            'labelExpression'=>'$data->article->title',
            'urlExpression'=> 'Yii::app()->controller->createUrl("revision/view", array("id" => $data->id));'
        ),
        array(
            'name' => 'authorFullName',
            'value' => '$data->user->getFullName()'
        ),
    ),
));