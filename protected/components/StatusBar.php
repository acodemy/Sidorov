<?php
class StatusBar extends CWidget
{
    public $status;

    public $id;

    public function init()
    {
        $action = Yii::app()->controller->action->id;
        $stage = $this->getStage($action);
        $status = isset($_GET['id']) ? Article::model()->findByPk($_GET['id'])->status : 1;
        $steps = array (
            array(
                'title' => 'Основные данные',
                'action' => 'submit',
                'number' => 1,
                'allow' => 'submit',
            ),
            array(
                'title' => 'Добавление соавторов',
                'action' => 'addcoauthors',
                'number' => 2,
                'allow' => 'addcoauthors',
            ),
            array(
                'title' => 'Добавление файлов',
                'action' => 'addfiles',
                'number' => 3,
                'allow' => 'addcoauthors',
            ),
            array(
                'title' => 'Добавление комментария',
                'action' => 'addcomment',
                'number' => 4,
                'allow' => 'addcomment',
            ),
            array(
                'title' => 'Подтверждение',
                'action' => 'confirm',
                'number' => 5,
                'allow' => 'addcomment',
            ),
        );

        $content = '';
        foreach ($steps as $step) {
            $z = ($step['number']==$stage) ? ' class="active" ' : '';
            $content .= "<li{$z}>";
            $ifIsIf = ($status >= ($step['number']+2));
            $content .= $ifIsIf ? '<a  href=.?r=/article/'.Article::returnNameStatus($step["number"]+2).'&id='.$_GET["id"].'>' : '<span style="padding:4px 15px!important;display:block;">';

            $content .= 'Шаг ' . ($step['number']) . '. ';
            $content .= $step['title'];
            $content .= $ifIsIf ? '</a>' : '</span>';
            $content .= '</li>';
        }
        echo '<ul class="nav nav-pills nav-stacked">' . $content . '</ul>';
    }

    public function run()
    {
        // этот метод будет вызван методом CController::endWidget()
    }

    private function getStage($action)
    {
        switch ($action)
        {
            case ('submit'):
                return 1;
                break;
            case ('addcoauthors'):
                return 2;
                break;
            case ('addfiles'):
                return 3;
                break;
            case ('addcomment'):
                return 4;
                break;
            case ('confirm'):
                return 5;
                break;
        }
    }


}