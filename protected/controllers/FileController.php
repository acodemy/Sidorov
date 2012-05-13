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
            $tempCoauthor = $this->loadModel($id);

            $article = Article::model()->findByPk($tempCoauthor->article_id);
            if ($tempCoauthor->count('article_id='.$article->id) == 1)
            {
                Article::statusUpdate($article->status, $article, Article::FILES_WAIT);
            }

            $tempCoauthor->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function loadModel($id) {
        $model = FileArticle::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404,'The requested page does not exist.');

        return $model;
    }
}
