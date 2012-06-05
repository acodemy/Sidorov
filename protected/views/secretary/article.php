<?php
    $this->breadcrumbs=array(
        'Secretary'=>array('/secretary'),
        'Article',
    );
?>



    <div class="rows">
        <dl class="dl-horizontal" style='text-overflow: clip;'>
            <dt><?php echo $article->getAttributeLabel('title');?></dt>
            <dd><?php echo $article->title; ?></dd>

            <dt><?php echo $article->getAttributeLabel('description');?></dt>
            <dd><?php echo $article->description; ?></dd>

            <dt>Авторы</dt>
            <dd><?php echo $article->author->getFullName(); ?></dd>
            <?php
            if ($article->coauthorsCount) {
                $coauthors = $article->coauthors;
                foreach ($coauthors as $coauthor) {
                    echo "<dd>{$coauthor->getFullName()}</dd>\n";
                }
            }
            ?>

            <?php if (!empty($article->comment)) : ?>
            <dt><?php echo $article->getAttributeLabel('comment');?></dt>
            <dd><?php echo $article->comment;  ?></dd>
            <?php endif; ?>
        </dl>
    </div>
    <?php echo CHtml::link('<i class="icon-download-alt"></i> Скачать для просмотра', $article->getArchiveLink(), array('class' => 'btn btn-info')); ?>
    <hr />
    <div class="form">
<?php

    if ($article->status > Article::REJECTED )
    {
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'revisions-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    ));

?>

    <div class="rows">
        Выберите рецензента
    <?php echo $form->label($model,''); ?>
    <?php echo $form->dropDownList($model,'login',$users);?>
    <?php echo $form->error($model,'login'); ?>
    </div>
    <div class="rows buttons">
        <?php echo CHtml::submitButton('Добавить рецензента', array('class' => 'btn btn-primary')); ?>
    </div>

    </div>

    <?php
    $this->endWidget();
?>

<?php
    $ajax = <<<AJAX
function() {
    var url = $(this).attr('href');
    $.post(url, function(response) {
        $.fn.yiiGridView.update('');
        alert(response);
    });
    return false;
}
AJAX;

    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $revisionsDP,
            'columns' => array(
                'user.last_name',
                array(
                    'name' => 'status',
                    'value' => 'Revision::getStatusName($data->status)'
                ),
                array(
                    'name' => 'Files',
                    'type' => 'raw',
                    'value' => '($data->status == 2) ? CHtml::button("Скачать") : "Рецензия ещё не сформирована"'
                ),
                'comment',
                array(
                    'class' => 'CButtonColumn',
                    'header' => 'Действия',
                    'buttons' => array(
                        'delete' => array(
                            'label' => 'Удалить',
                            'url' => 'Yii::app()->controller->createUrl("revision/action", array("id" => $data->primaryKey, "action" => "delete"))',
                        ),
                        'approve' => array(
                            'label' => 'Утвердить',
                            'url' => 'Yii::app()->controller->createUrl("revision/action", array("id" => $data->primaryKey, "action" => "approve"))',
                            'click' => 'function() {
    var url = $(this).attr("href");
    $.post(url, function(response) {
        $.fn.yiiGridView.update(this);
        alert(response);
    });
    return false;
}'
                        ),
                        'disapprove' => array(
                            'label' => 'Отвергнуть',
                            'url' => 'Yii::app()->controller->createUrl("revision/action", array("id" => $data->primaryKey, "action" => "disapprove"))',

                        ),
                    ),
                    'deleteConfirmation' => 'Вы уверены, что хотите удалить рецензию? Данное действие будет невозможно отменить!',
                    'template' => '{approve} {disapprove} | {delete}'
                ),
            ),
            'htmlOptions' => array(
                'class' => 'table table-striped'
            ),
       )
    );

    }
     ?>