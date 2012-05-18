<?php

$this->pageTitle=Yii::app()->name . ' - Личный кабинет';
$this->breadcrumbs=array(
    'index',
);
?>

<h1>Личный кабинет</h1>

<?php

$this->widget('zii.widgets.CMenu', array(
    'items'=>array(
        // Important: you need to specify url as 'controller/action',
        // not just as 'controller' even if default acion is used.
        array('label'=>'Добавить статью ' , 'url'=>array('article/submit')),
        array('label'=>'Отклоненные статьи: ' . (isset($model['REJECTED']) ? $model['REJECTED'] : 0), 'url'=>array('article/browsing', 'status_id'=>$this->_status['REJECTED'], 'user_id'=>$model['user'])),
        // 'Products' menu item will be selected no matter which tag parameter value is since it's not specified.
        array('label'=>'Опубликованые статьи:' . (isset($model['PUBLISHED']) ? $model['PUBLISHED'] : 0), 'url'=>array('article/browsing', 'status_id'=>$this->_status['PUBLISHED'], 'user_id'=>$model['user'])),
        array('label'=>'Ожидающие рецензии:' . (isset($model['UNDER_REVISION']) ? $model['UNDER_REVISION'] : 0), 'url'=>array('article/browsing', 'status_id'=>$this->_status['UNDER_REVISION'], 'user_id'=>$model['user'])),
        array('label'=>'Ожидающие добавления соавторов:' . (isset($model['COAUTHORS_WAIT']) ? $model['COAUTHORS_WAIT'] : 0), 'url'=>array('article/browsing', 'status_id'=>$this->_status['COAUTHORS_WAIT'], 'user_id'=>$model['user'])),
        array('label'=>'Ожидающие добавления файлов:' . (isset($model['FILES_WAIT']) ? $model['FILES_WAIT'] : 0), 'url'=>array('article/browsing', 'status_id'=>$this->_status['FILES_WAIT'], 'user_id'=>$model['user'])),
        array('label'=>'Ожидающие добавления комментария:' . (isset($model['COMMENTS_WAIT']) ? $model['COMMENTS_WAIT'] : 0), 'url'=>array('article/browsing', 'status_id'=>$this->_status['COMMENTS_WAIT'], 'user_id'=>$model['user'])),
        array('label'=>'Ожидающие подтверждения:' . (isset($model['CONFIRM_WAIT']) ? $model['CONFIRM_WAIT'] : 0), 'url'=>array('article/browsing', 'status_id'=>$this->_status['CONFIRM_WAIT'], 'user_id'=>$model['user'])),
        array('label'=>'На доработке:' . (isset($model['REWORK']) ? $model['REWORK'] : 0), 'url'=>array('article/browsing', 'status_id'=>$this->_status['CONFIRM_WAIT'], 'user_id'=>$model['user'])),


    ),
)); ?>

