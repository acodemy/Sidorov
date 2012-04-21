<?php
class Logs extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'logs';
    }
}
/**
 * Created by JetBrains PhpStorm.
 * User: Денис
 * Date: 21.04.12
 * Time: 13:49
 * To change this template use File | Settings | File Templates.
 */