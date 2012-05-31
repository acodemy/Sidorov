<?php
class StatusBar extends CWidget
{
    public $status;

    public $id;

    public function init()
    {
        $action = Yii::app()->controller->action->id;
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
            $content .= 'Шаг ' . ($step['number']) . '. ';
            $content .= $step['title'];
            $content .= '</li>';
        }
        echo '<ul>' . $content . '</ul>';
    }

    public function run()
    {
        // этот метод будет вызван методом CController::endWidget()
    }

}