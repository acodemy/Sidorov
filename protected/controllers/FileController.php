<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jarosluv
 * Date: 12.05.12
 * Time: 0:09
 * To change this template use File | Settings | File Templates.
 */
class FileController extends Controller
{
    public function actionDelete ($id) {
        if(Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();

            if(!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('site/main'));
            }
        }
        else
            throw new CHttpException(400, 'Неверный запрос. Пожалуйста, не пробуйте его снова.');

    }

    public function loadModel($id) {
        $model = FileArticle::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'Запрошенной модели не существует.');

        return $model;
    }
}
