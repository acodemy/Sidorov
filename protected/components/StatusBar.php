<?php
class StatusBar extends CWidget
{
    public $status;

    public $id;

    public function init()
    {
        $action = Yii::app()->controller->action->id;
        $stage = $this->getStage($action);
        $status = Article::model()->findByPk($_GET['id'])->status;
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
            $content .= '<li>';
            $ifIsIf = ($status >= ($step['number']+2));
            $content .= $ifIsIf ? '<a href=.?r=/article/'.Article::returnNameStatus($step["number"]+2).'&id='.$_GET["id"].'>' : '';
            $content .= ($step['number']==$stage) ? '<i class="icon-forward"></i>' : '';
            $content .= 'Шаг ' . ($step['number']) . '. ';
            $content .= $step['title'];
            $content .= $ifIsIf ? '</a>' : '';
            $content .= '</li>';
        }
        echo '<ul>' . $content . '</ul>';
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