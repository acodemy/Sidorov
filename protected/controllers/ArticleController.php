<?php

class ArticleController extends Controller
{
    public function actionBrowsing() {
        if(isset($_GET['status_id']) && Yii::app()->user->checkAccess('seeForArticle', array('user' => $_GET['user_id'])))
        {
            $model = array("user" => (int) $_GET['user_id'], "status" => (int) $_GET['status_id']);
            $model['dataProvider'] = new CActiveDataProvider('Article', array(
                'criteria'=>array(
                    'condition'=>'user_id =:User AND status =:Status',
                    'params'=>array(':User' => $model['user'], ':Status' => $model['status'])
                ),
            ));
            $this->render('browsing',array('model'=>$model));
        } else {
            Yii::app()->user->setFlash('browsing','У вас нет доступа к данным.');
            $this->render('browsing');
        }
    }

    public function actionSubmit () {
        /**
         * Проверка - добавляется новая статья или редактируется уже существующая.
         * Проверка прав на добавление/редактирование.
         * Проверка статуса при редактировании статьи.
         */
        if (!isset($_GET['id'])) {
            $article = new Article();
            $access = Yii::app()->user->checkAccess('createArticle');
            $statusCheck = true;
        }   else {
            $article = $this->loadModel($_GET['id']);
            $access = Yii::app()->user->checkAccess('ownArticle', array('article' => $article));
            $statusCheck = in_array($article->status, array(Article::COAUTHORS_WAIT, Article::FILES_WAIT, Article::COMMENTS_WAIT, Article::CONFIRM_WAIT));
        }

        if ($access && $statusCheck) {
            if (isset($_POST['Article'])) {
                $article->attributes = $_POST['Article'];
                if($article->validate()) {
                    $article->user_id = Yii::app()->user->id;
                    if (isset($article->status)) {
                        $article->status = Article::COAUTHORS_WAIT;
                    }
                    $article->save();
                    $this->redirect($this->createUrl('article/addcoauthors', array('id' => $article->id)));
                }
            }

            $this->render('submit', array('model' => $article));
        } else {
            Yii::app()->user->setFlash('browsing','У вас нет доступа к данным.');
            $this->render('browsing');
        }
    }

    public function actionAddCoauthors () {
        if (isset($_GET['id'])) {
            $article = $this->loadModel($_GET['id']);
            $access = Yii::app()->user->checkAccess('ownArticle', array('article' => $article));
            $statusCheck = in_array($article->status, array(Article::COAUTHORS_WAIT, Article::FILES_WAIT, Article::COMMENTS_WAIT, Article::CONFIRM_WAIT));
        } else {
            Yii::app()->user->setFlash('addcoauthors','Вы ошибочно попаи на данную страницу.');
            $this->render('addcoauthors');
        }

        if ($access && $statusCheck) {
            $coauthor = new Coauthor();
            if (isset($_POST['Coauthor'])) {
                $coauthor->attributes = $_POST['Coauthor'];
                if($coauthor->validate()) {
                    $coauthor->article_id = $article->id;
                    $coauthor->save();

                    $article->status = Article::FILES_WAIT;
                    $article->save();

                    $this->redirect($this->createUrl('article/addcoauthors', array('id' => $article->id)));
                }
            }

            $this->render('addcoauthors', array('model' => $coauthor, 'id' => $article->id, 'status' => $article->status));
        } else {
            Yii::app()->user->setFlash('addcoauthors','У вас нет доступа к данным.');
            $this->render('addcoauthors');
        }
    }

    public function actionAddFiles () {
        if (isset($_GET['id'])) {
            $article = $this->loadModel($_GET['id']);
            $access = Yii::app()->user->checkAccess('ownArticle', array('article' => $article));
            $statusCheck = in_array($article->status, array(Article::COAUTHORS_WAIT, Article::FILES_WAIT, Article::COMMENTS_WAIT, Article::CONFIRM_WAIT));
        } else {
            Yii::app()->user->setFlash('addfiles','Вы ошибочно попаи на данную страницу.');
            $this->render('addfiles');
        }

        /**
         * Если при переходе на страницу добавления файлов
         * статус статьи COAUTHORS_WAIT, то меняем его на FILES_WAIT
         */
        if ((int)$article->status === Article::COAUTHORS_WAIT) {
            $article->status = Article::FILES_WAIT;
            $article->save();
        }

        if ($access && $statusCheck) {
            $file = new FileArticle();
            if (!empty($_FILES)) {
                $filesPath = $article->getDirectoryPath();
                if (!is_dir($filesPath)) {
                    mkdir($filesPath);
                }
                $upload = new Upload(str_replace('//', '/', $filesPath));
                if ($upload->uploads($_FILES['file'])) {
                    $fileInfo = $upload->getFilesInfo();
                    $file->title = $fileInfo['name'];
                    $file->filename = $fileInfo['nameTranslit'];
                    $file->article_id = $article->id;

                    if ($file->validate()) {
                        $file->save();

                        $article->status = Article::COMMENTS_WAIT;
                        $article->save();

                        $this->redirect($this->createUrl('article/addfiles', array('id' => $article->id)));
                    }
                }
            }

            $this->render('addfiles',array('model' => $file, 'id' => $article->id, 'status' => $article->status));
        } else {
            Yii::app()->user->setFlash('addcoauthors','У вас нет доступа к данным.');
            $this->render('addfiles');
        }
    }

    public function actionAddComment () {
        if (isset($_GET['id'])) {
            $article = $this->loadModel($_GET['id']);
            $access = Yii::app()->user->checkAccess('ownArticle', array('article' => $article));
            $statusCheck = in_array($article->status, array(Article::COMMENTS_WAIT, Article::CONFIRM_WAIT));
        } else {
            Yii::app()->user->setFlash('addcomment','Вы ошибочно попаи на данную страницу.');
            $this->render('addcomment');
        }

        if ($access && $statusCheck) {
            if (isset($_POST['Article'])) {
                $article->comment = $_POST['Article']['comment'];
                if($article->validate()) {
                    $article->status = Article::CONFIRM_WAIT;
                    $article->save();

                    $this->redirect($this->createUrl('article/confirm', array('id' => $article->id)));
                }
            }

            $this->render('addcomment', array('model' => $article));
        } else {
            Yii::app()->user->setFlash('addcomment','У вас нет доступа к данным.');
            $this->render('addcomment');
        }
    }

    public function actionConfirm () {
        if (isset($_GET['id'])) {
            $article = $this->loadModel($_GET['id']);
            $access = Yii::app()->user->checkAccess('ownArticle', array('article' => $article));
            $statusCheck = in_array($article->status, array(Article::CONFIRM_WAIT));
        } else {
            Yii::app()->user->setFlash('confirm','Вы ошибочно попаи на данную страницу.');
            $this->render('confirm');
        }

        if ($access && $statusCheck) {
            if (isset($_POST['submit'])) {
                $article->status = Article::UNDER_REVISION;
                $article->save();

                $articleFiles = $article->files;
                $directory = $article->getDirectoryPath();
                $filename = $article->getDirectoryName() . '.zip';
                $zip = new ZipArchive();
                if ($zip->open($directory . $filename, ZipArchive::CREATE) === true) {
                    foreach ($articleFiles as $file) {
                        $title = iconv('utf-8', 'cp866', $file->title);
                        $zip->addFile($directory . $file->filename, $title);
                    }
                    $zip->setArchiveComment($article->title);
                    $zip->close();
                } else {
                    throw new CHttpException(404, 'Архив не создался.');
                }

                //$this->redirect($this->createUrl('site/index', array('id' => $article->id)));
            }

            $this->render('confirm', array('model' => $article));
        } else {
            Yii::app()->user->setFlash('confirm','У вас нет доступа к данным.');
            $this->render('confirm');
        }
    }

    public function loadModel($id) {
        $model = Article::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
    }

    public function actionDecline($id)
    {
        if(Yii::app()->request->isPostRequest) {
            $article = $this->loadModel($id, 'Article');
            $article->status = Article::REJECTED;
            $article->save();


           // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionToPrint($id)
    {
        if(Yii::app()->request->isPostRequest) {
            $article = Article::model()->findByPk($id);
            $article->status = 2;
            $article->save();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');

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