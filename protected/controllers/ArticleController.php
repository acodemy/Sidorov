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
            $article->attributes=$_POST['Article'];
            if($article->validate()) {
                $article->user_id = Yii::app()->user->id;
                $article->save();

                $this->redirect($this->createUrl('article/addcoauthors', array('id' => $article->id)));
            }
        }
        $this->render('submit',array('model'=> $article));
    }

    public function actionAddCoauthors () {
        $article = new Article();

        $coauthor = new Coauthor();
        if (isset($_POST['Coauthor'])) {
           $coauthor->attributes = $_POST['Coauthor'];
           if($coauthor->validate()) {
               $coauthor->article_id = $_GET['id'];//$article->id;

               $coauthor->save();

               $this->redirect($this->createUrl('article/addcoauthors', array('id' => (int)$_GET['id'])));
           }
       }
       $this->render('addcoauthors',array('model'=> $article));

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
        if ((int)$article->status === 4) {
            $article->status = 5;
            $article->save();
        }

        $file = new FileArticle();
        if (!empty($_FILES)) {
            if (!empty($_FILES)) {
                $tempFile = $_FILES['file']['tmp_name'];
                $md5 = substr(md5($article->title), 0, 8);
                $targetPath = "files/{$md5}/";
                if (!is_dir($targetPath)) {
                    mkdir($targetPath);
                }
                $upload = new Upload(str_replace('//', '/', $targetPath));
                if ($upload->uploads($_FILES['file'])) {
                    $fileInfo = $upload->getFilesInfo();
                }
            }
            $file->title = $fileInfo['name'];
            $file->filename = $fileInfo['nameTranslit'];
            $file->article_id = $article->id;
            if($file->validate()) {
               $file->save();

               $this->redirect($this->createUrl('article/addfiles', array('id' => $article->id)));
            }
       }
       $this->render('addfiles',array('model'=> $article));
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
            $article->status = 6;
            $article->save();
        } else {
            $this->redirect($this->createUrl('article/addfiles', array('id' => (int)$_GET['id'])));
        }

        if (isset($_POST['Article'])) {
            $article->comment = $_POST['Article']['comment'];
            if($article->validate()) {
                $article->status = 7;
                $article->save();
                $this->redirect($this->createUrl('article/confirm', array('id' => (int)$_GET['id'])));
            }
        }

        $this->render('addcomment',array('model'=> $article));
    }

    public function actionConfirm () {
        /**
         * Создание объекта статьи, с которой происходит работа
         */
        $article = Article::model()->findByPk($_GET['id']);

        /**
         * Простая проверка на доступ
         */
        if ((int)$article->status < 6) {
            $this->redirect($this->createUrl('/index', array('id' => (int)$_GET['id'])));
        }

        if (isset($_POST['yt0'])) {

            $article->status = 3;
            $article->save();
            $this->redirect($this->createUrl('site/index', array('id' => (int)$_GET['id'])));
        }

        $this->render('confirm',array('model'=> $article));

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