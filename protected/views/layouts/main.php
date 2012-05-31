<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="ru" />

    <!-- bootstrap CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/template/css/bootstrap.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/template/css/bootstrap-responsive.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/template/js/bootstrap.min.js" />

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <style type='text/css'>
        .items {
            width:100%;
        }
        span.required {
            color:red;
            font-weight: bold;
        }
        .errorMessage {
            color:red;
        }
        input[type="checkbox"] {
            float:left;
            margin-right: 8px;
        }
        .note {
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container" id="page">

        <header style="padding:10px 0 20px;">
            <div id="logo"><h1><?php echo CHtml::encode(Yii::app()->name); ?></h1></div>
        </header><!-- header -->



        <div id="mainmenu">
            <?php $this->widget('zii.widgets.CMenu',array(
            'id' => 'main-menu',
            'encodeLabel' => false,
            'items'=>array(
                array('label' => '<i class="icon-home"></i> Главная', 'url' => array('/site/index')),
                array('label' => '<i class="icon-briefcase"></i> Личный кабинет', 'url' => array('site/main'), 'visible' => !Yii::app()->user->isGuest),
                array('label' => '<i class="icon-list-alt"></i> Кабинет секретаря', 'url' => array('secretary/index'), 'visible' => Yii::app()->user->checkAccess('secretary')),
                array('label' => '<i class="icon-user"></i> Войти', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                array('label' => '<i class="icon-pencil"></i> Регистрация', 'url' => array('/site/register'), 'visible' => Yii::app()->user->isGuest),
                array('label' => '<i class="icon-off"></i> Выйти (<b>' . Yii::app()->user->name . '</b>)', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
            ),
            'htmlOptions' => array(
                'class' => 'nav nav-tabs',
            ),
        )); ?>
        </div><!-- mainmenu -->

        <div class="page-header">
            <h2><?php
                echo Yii::app()->controller->title;
                ?></h2>
        </div>
        <?php echo $content; ?>
        <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links'=>$this->breadcrumbs,
            'htmlOptions' => array(
                'class' => 'breadcrumb',
            )
        )); ?><!-- breadcrumbs -->
        <?php endif?>
        <div class="clear"></div>

        <footer class="footer" style="margin-top:20px;">
            Copyright &copy; <?php echo date('Y'); ?> Саратовский государственный университет.<br/>
            Все права защищены.<br/>
            <?php echo Yii::powered(); ?>
        </footer><!-- footer -->

    </div><!-- page -->
</body>
</html>
