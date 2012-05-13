<?php

$this->pageTitle=Yii::app()->name . ' - Личный кабинет';
$this->breadcrumbs=array(
    'index',
);

if(Yii::app()->user->hasFlash('index')):
    ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('index'); ?>
</div>
<?php else: ?>

<h1>Личный кабинет</h1>

<?php



$this->widget('zii.widgets.CMenu', array(
    'items'=>array(
        // Important: you need to specify url as 'controller/action',
        // not just as 'controller' even if default acion is used.
        array('label'=>'Добавить статью ' , 'url'=>array('article/submit')),
        array('label'=>'Отклоненные статьи: ' . (isset($model['REJECTED']) ? $model['REJECTED'] : 0), 'url'=>array('article/browsing', 'status_id'=>1, 'user_id'=>$model['user'])),
        // 'Products' menu item will be selected no matter which tag parameter value is since it's not specified.
        array('label'=>'Опубликованые статьи:' . (isset($model['PUBLISHED']) ? $model['PUBLISHED'] : 0), 'url'=>array('article/browsing', 'status_id'=>2, 'user_id'=>$model['user'])),
        array('label'=>'Ожидающие рецензии:' . (isset($model['UNDER_REVISION']) ? $model['UNDER_REVISION'] : 0), 'url'=>array('article/browsing', 'status_id'=>3, 'user_id'=>$model['user'])),
        array('label'=>'Ожидающие добавления соавторов:' . (isset($model['COAUTHORS_WAIT']) ? $model['COAUTHORS_WAIT'] : 0), 'url'=>array('article/browsing', 'status_id'=>4, 'user_id'=>$model['user'])),
        array('label'=>'Ожидающие добавления файлов:' . (isset($model['FILES_WAIT']) ? $model['FILES_WAIT'] : 0), 'url'=>array('article/browsing', 'status_id'=>5, 'user_id'=>$model['user'])),
        array('label'=>'Ожидающие добавления комментария:' . (isset($model['COMMENTS_WAIT']) ? $model['COMMENTS_WAIT'] : 0), 'url'=>array('article/browsing', 'status_id'=>6, 'user_id'=>$model['user'])),
        array('label'=>'Ожидающие подтверждения:' . (isset($model['CONFIRM_WAIT']) ? $model['CONFIRM_WAIT'] : 0), 'url'=>array('article/browsing', 'status_id'=>7, 'user_id'=>$model['user'])),
        array('label'=>'На доработке:' . (isset($model['REWORK']) ? $model['REWORK'] : 0), 'url'=>array('article/browsing', 'status_id'=>8, 'user_id'=>$model['user'])),


    ),
));
    endif; ?>

