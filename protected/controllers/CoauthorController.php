<?php

class CoauthorController extends Controller
{
    public function actionDelete ($id) {
        if(Yii::app()->request->isPostRequest) {
            $tempCoauthor = $this->loadModel($id);
            $article = Article::model()->findByPk($tempCoauthor->article_id);
            if ($tempCoauthor->count('article_id='.$article->id) == 1)
            {
            Article::statusUpdate($article->status, $article, Article::COAUTHORS_WAIT);
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
        $model = Coauthor::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404,'The requested page does not exist.');

        return $model;
    }
}

