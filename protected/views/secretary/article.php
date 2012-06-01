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
            <dd><?php echo $article->comment; ?></dd>
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

    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider2,
            'columns'=>array(
                array(
                    'name' => 'user_id',
                    'type' => 'raw',
                    'value' => 'User::model()->findByPk($data->user_id)->login',
                    ),
                'status',
                array(
                    'name' => 'Files',
                    'type' => 'raw',
                    'value' => '($data->status == 2) ? CHtml::button("Скачать") : "Рецензия ещё не сформирована"'
                ),
                array(
                    'name' => 'is_positive',
                    'type' => 'raw',
                    'value' => 'Revisions::getNamePositive($data->is_positive)'
                ),
                array(
                    'class' => 'CButtonColumn',
                'buttons' => array(
                    'delete' => array(
                        'url' => 'Yii::app()->controller->createUrl("reviewer/delete", array("id" => $data->primaryKey))',
                    ),
                    'prove' => array(
                        'label' => 'prove',
                        'url' => 'Yii::app()->controller->createUrl("reviewer/approve", array("id" => $data->primaryKey))',
                    ),// icon-thumbs-up
                    'approve' => array(
                        'label' => 'approve',
                        'url' => 'Yii::app()->controller->createUrl("reviewer/disapprove", array("id" => $data->primaryKey))',
                    ),// icon-thumbs-up
                ),

                'template' => '{delete} {prove} {approve}'
                ),
            ),
            'htmlOptions' => array(
                'class' => 'table table-striped'
            ),

       )
    );

    }
     ?>