<?php

class ArticleController extends Controller
{
	public function actionIndex()
	{
        // Тут должна быть менюшка со списком действий, применительно к статье
	}

    public function actionSubmit () {
        $article = new Article();

        if (isset($_POST['Article'])) {
            $article->attributes = $_POST['Article'];

            if($article->validate()) {
                $article->user_id = Yii::app()->user->id;
                $article->status = Article::COAUTHORS_WAIT;

                $article->save();

                $this->redirect($this->createUrl('article/addcoauthors', array('id' => $article->id)));
            }
        }

        $this->render('submit', array('model' => $article));
    }

    public function actionAddCoauthors () {
        $article = Article::model()->findByPk($_GET['id']);
        $coauthor = new Coauthor();

        if (isset($_POST['Coauthor'])) {
            $coauthor->attributes = $_POST['Coauthor'];

            if($coauthor->validate()) {
                $coauthor->article_id = $article->id;

                $coauthor->save();

                $this->redirect($this->createUrl('article/addcoauthors', array('id' => $article->id)));
            }
        }

        $this->render('addcoauthors', array('model' => $coauthor, 'id' => $article->id));
    }

    public function actionAddFiles () {
        /**
         * Создание объекта статьи, с которой происходит работа
         */
        $article = Article::model()->findByPk($_GET['id']);

        /**
         * Если при переходе на страницу добавления файлов
         * статус статьи COAUTHORS_WAIT, то меняем его на FILES_WAIT
         */
        if ((int)$article->status === Article::COAUTHORS_WAIT) {
            $article->status = Article::FILES_WAIT;
            $article->save();
        }

        $file = new FileArticle();
        if (!empty($_FILES)) {
            $filesPath = $article->getDirectory();
            if (!is_dir($filesPath)) {
                mkdir($filesPath);
            }

            $upload = new Upload(str_replace('//', '/', $filesPath));
            if ($upload->uploads($_FILES['file'])) {
                $fileInfo = $upload->getFilesInfo();
                $file->title = $fileInfo['name'];
                $file->filename = $fileInfo['nameTranslit'];
                $file->article_id = $article->id;

                if($file->validate()) {
                    $file->save();

                    $this->redirect($this->createUrl('article/addfiles', array('id' => $article->id)));
                }
            }
        }

        $this->render('addfiles',array('model' => $file, 'id' => $article->id));
    }

    public function actionAddComment () {
        /**
         * Создание объекта статьи, с которой происходит работа
         */
        $article = Article::model()->findByPk($_GET['id']);

        /**
         * Смена статуса при наличии добавленных к статье файлов,
         * иначе переход к добавлению файлов
         */
        if ($article->hasFiles()) {
            $article->status = Article::COMMENTS_WAIT;
            $article->save();
        } else {
            $this->redirect($this->createUrl('article/addfiles', array('id' => $article->id)));
        }

        if (isset($_POST['Article'])) {
            $article->comment = $_POST['Article']['comment'];

            if($article->validate()) {
                $article->status = Article::CONFIRM_WAIT;
                $article->save();

                $this->redirect($this->createUrl('article/confirm', array('id' => $article->id)));
            }
        }

        $this->render('addcomment', array('model' => $article));
    }

    public function actionConfirm () {
        /**
         * Создание объекта статьи, с которой происходит работа
         */
        $article = Article::model()->findByPk($_GET['id']);

        /**
         * Простая проверка на доступ
         */
        if ((int)$article->status < Article::COMMENTS_WAIT) {
            $this->redirect($this->createUrl('article/addfiles', array('id' => $article->id)));
        }

        if (isset($_POST['submit'])) {
            $article->status = Article::UNDER_REVISION;
            $article->save();

            $this->redirect($this->createUrl('site/index', array('id' => $article->id)));
        }

        $this->render('confirm', array('model' => $article));
    }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/

}