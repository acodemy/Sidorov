<?php
class StatusBar extends CWidget
{
    public $status;

    public $id;

    public function init()
    {
        for ($i = 3; $i < 8; $i++)
        {
            $tempStatusStringLink = ($this->status >= $i) ? CHtml::link(($i-3).') '.Article::getStatusAuthor($i), '?r=article/'.Article::returnNameStatus($i).'&id='.$this->id) : ($i-3).') '.Article::getStatusAuthor($i).' (этап не доступен)' ;
            echo "<p>".$tempStatusStringLink."</p>";
        }

        // этот метод будет вызван методом CController::beginWidget()
    }

    public function run()
    {
        // этот метод будет вызван методом CController::endWidget()
    }

}
/**
 * Created by JetBrains PhpStorm.
 * User: Денис
 * Date: 11.05.12
 * Time: 0:16
 * To change this template use File | Settings | File Templates.
 */