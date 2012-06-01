<?php
    $title = 'Личный кабинет';
    $this->pageTitle = Yii::app()->name . ' — ' . $title;
    $this->breadcrumbs = array($title);
?>

<div class="well" style="margin-top:10px; padding: 8px 0;">
<?php if (Yii::app()->user->checkAccess('secretary')) : ?>
    <?php
        $this->widget('zii.widgets.CMenu', array(
            'items' => array(
                array('label' => 'Меню секретаря',
                    'itemOptions' => array(
                        'class' => 'nav-header',
                    ),
                ),
                array('label' => 'Статьи, ожидающие проверки (' . ($moderate['articles']) . ')',
                    'url' => array('secretary/index')
                ),
                array('label' => 'Рецензии, ожидающие проверки (' . ($moderate['revisions']) . ')',
                    'url' => array('secretary/revision')
                ),
            ),
            'htmlOptions' => array(
                'class' => 'nav nav-list',
            ),
        ));
    ?>
<?php endif; ?>

<?php if (Yii::app()->user->checkAccess('author')) : ?>

        <?php
            $this->widget('zii.widgets.CMenu', array(
                'encodeLabel' => false,
                'items'=>array(
                    array(
                        'label' => 'Меню автора',
                        'itemOptions' => array(
                            'class' => 'nav-header',
                            'style' => 'margin-top:15px;',
                        ),
                    ),
                    array('label' => '<b>Добавить статью</b>' , 'url' => array('article/submit')),
                    array('label'=>'Отклоненные статьи (' . (isset($model['REJECTED']) ? $model['REJECTED'] : 0) . ')', 'url'=>array('article/browsing', 'status' => 1)),
                    array('label'=>'Опубликованые статьи (' . (isset($model['PUBLISHED']) ? $model['PUBLISHED'] : 0) . ')', 'url'=>array('article/browsing', 'status' => 2)),
                    array('label'=>'Ожидающие рецензии (' . (isset($model['UNDER_REVISION']) ? $model['UNDER_REVISION'] : 0) . ')', 'url'=>array('article/browsing', 'status' => 3)),
                    array('label'=>'Ожидающие добавления соавторов (' . (isset($model['COAUTHORS_WAIT']) ? $model['COAUTHORS_WAIT'] : 0) . ')', 'url'=>array('article/browsing', 'status' => 4)),
                    array('label'=>'Ожидающие добавления файлов (' . (isset($model['FILES_WAIT']) ? $model['FILES_WAIT'] : 0) . ')', 'url'=>array('article/browsing', 'status' => 5)),
                    array('label'=>'Ожидающие добавления комментария (' . (isset($model['COMMENTS_WAIT']) ? $model['COMMENTS_WAIT'] : 0) . ')', 'url'=>array('article/browsing', 'status' => 6)),
                    array('label'=>'Ожидающие подтверждения (' . (isset($model['CONFIRM_WAIT']) ? $model['CONFIRM_WAIT'] : 0) . ')', 'url'=>array('article/browsing', 'status' => 7)),
                    array('label'=>'На доработке (' . (isset($model['REWORK']) ? $model['REWORK'] : 0) . ')', 'url'=>array('article/browsing', 'status' => 8)),
                ),
               'htmlOptions' => array(
                   'class' => 'nav nav-list',
               ),
            ));
        ?>
<?php endif; ?>

<?php if (Yii::app()->user->checkAccess('revisor')) : ?>
    <?php
        $this->widget('zii.widgets.CMenu', array(
            'items' => array(
                array('label' => 'Меню рецензента',
                    'itemOptions' => array(
                        'class' => 'nav-header',
                        'style' => 'margin-top:15px;',
                    ),
                ),
                array('label' => 'Список статей к рецензированию (' . ($revisions['wait']) . ')',
                      'url' => array('revision/articleslist')
                ),
                array('label' => 'Список рецензий (' . ($revisions['all']) . ')',
                      'url' => array('revision/list')
                ),
            ),
            'htmlOptions' => array(
                'class' => 'nav nav-list',
            ),
        ));
    ?>
<?php endif; ?>
</div>