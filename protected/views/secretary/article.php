<?php
$this->breadcrumbs=array(
	'Secretary'=>array('/secretary'),
	'Article',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
    <?php

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$dataProvider,
        'columns'=>array(
            array(
                'name' => 'title',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode($data->title), "index.php?r=secretary/article"."&id=".$data->id)',
                ),
            'description',
            'comment',
            array(
                'name' => 'section_id',
                'type' => 'raw',
                'value' => 'Section::model()->findByPk($data->section_id)->name;'
            ),
            array(
                'name' => 'status',
                'type' => 'raw',
                'value' => 'Article::getNameStatus($data->status)'
            ),
            array(
                'name' => 'Files',
                'type' => 'raw',
                'value' => 'CHtml::button("Скачать")'
                ),
            array(
                'class' => 'ButtomApprove',
                'buttons' => array(
                    'delete' => array(
                        'imageUrl' => '/images/disapprove.png',
                        'url' => 'Yii::app()->controller->createUrl("article/decline", array("id" => $data->primaryKey))',
                    ),
                ),
                'template' => '{delete}'
                ),
            array(
                'class' => 'ButtomProve',
                'buttons' => array(
                    'delete' => array(
                        'imageUrl' => '/images/approve.png',
                        'url' => 'Yii::app()->controller->createUrl("article/toprint", array("id" => $data->primaryKey))',
                    ),
                ),
                'template' => '{delete}'
            ),
            )
         )

    );
    if ($article > Article::REJECTED )
    {
    echo '<p>Рецензии:</p>';
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'revisions-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    ));
    ?>
    <div class="row">
    <?php echo $form->label($model,'Add reviewer: '); ?>
    <?php echo $form->dropDownList($model,'login',$users);?>
    <?php echo $form->error($model,'login'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Add'); ?>
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
                    'value' => '($data->status == 3) ? CHtml::button("Скачать") : "Рецензия ещё не сформирована"'
                ),
                array(
                    'name' => 'positive',
                    'type' => 'raw',
                    'value' => 'Revisions::getNamePositive($data->positive)'
                ),
                array(
                    'class' => 'CButtonColumn',
                'buttons' => array(
                    'delete' => array(
                        'url' => 'Yii::app()->controller->createUrl("reviewer/delete", array("id" => $data->primaryKey))',
                    ),
                ),
                'template' => '{delete}'
                ),
                array(
                    'class' => 'ButtomProve',
                    'buttons' => array(
                        'delete' => array(
                            'imageUrl' => '/images/approve.png',
                            'url' => 'Yii::app()->controller->createUrl("reviewer/approve", array("id" => $data->primaryKey))',
                        ),
                 ),
                    'template' => '{delete}'
                ),
                array(
                    'class' => 'ButtomApprove',
                    'buttons' => array(
                        'delete' => array(
                            'imageUrl' => '/images/disapprove.png',
                            'url' => 'Yii::app()->controller->createUrl("reviewer/disapprove", array("id" => $data->primaryKey))',

                        ),
                    ),
                    'template' => '{delete}'
                ),
            )

       )
    );
    }
     ?>
</p>
