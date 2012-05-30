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
        if (!isset($_GET['id'])) {
            $article = new Article();
            $access = Yii::app()->user->checkAccess('createArticle');
        }   else {
            $article = $this->loadModel($_GET['id']);
            $access = Yii::app()->user->checkAccess('ownArticle', array('article_id' => $_GET['id']));
        }

        if ($access) {
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
        $statusCheck = $_GET['id'] ? ((Article::model()->findByPk($_GET['id'])->status < 4) ? false : true) : false;
        $access = Yii::app()->user->checkAccess('changeArticle', array('article_id' => $_GET['id']));

        if(isset($_GET['id']) && $access && $statusCheck) {
        $article = Article::model()->findByPk($_GET['id']);
        $coauthor = new Coauthor();

        if (isset($_POST['Coauthor'])) {
            $coauthor->attributes = $_POST['Coauthor'];

            if($coauthor->validate()) {
                $coauthor->article_id = $article->id;

                $coauthor->save();

                Article::statusUpdate(Article::COAUTHORS_WAIT, $article);

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
        /**
         * Создание объекта статьи, с которой происходит работа
         */
        $statusCheck = $_GET['id'] ? ((Article::model()->findByPk($_GET['id'])->status < 5) ? false : true) : false;
        $access = Yii::app()->user->checkAccess('changeArticle', array('article_id' => $_GET['id']));

        if(isset($_GET['id']) && $access && $statusCheck) {

        $article = Article::model()->findByPk($_GET['id']);

        /**
         * Если при переходе на страницу добавления файлов
         * статус статьи COAUTHORS_WAIT, то меняем его на FILES_WAIT
         */
        /*if ((int)$article->status === Article::COAUTHORS_WAIT) {
            $article->status = Article::FILES_WAIT;
            $article->save();
        }       */
        //добавил измнение статуса в добавлениа авторов, требуется из-за проверки прав вначала данного экшена

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

                    Article::statusUpdate(Article::FILES_WAIT, $article);

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
        /**
         * Создание объекта статьи, с которой происходит работа
         */
        $statusCheck = $_GET['id'] ? ((Article::model()->findByPk($_GET['id'])->status < 6) ? false : true) : false;
        $access = Yii::app()->user->checkAccess('changeArticle', array('article_id' => $_GET['id']));

        if(isset($_GET['id']) && $access && $statusCheck) {
        $article = Article::model()->findByPk($_GET['id']);

        /**
         * Смена статуса при наличии добавленных к статье файлов,
         * иначе переход к добавлению файлов
         */
        if (!$article->hasFiles()) {

        //удвлил, потому что в экшене файлов статус меняется, при добавлении файлов

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
        } else {
            Yii::app()->user->setFlash('addcoauthors','У вас нет доступа к данным.');
            $this->render('addcomment');
        }
    }

    public function actionConfirm () {
        /**
         * Создание объекта статьи, с которой происходит работа
         */
        $statusCheck = $_GET['id'] ? ((Article::model()->findByPk($_GET['id'])->status < 7) ? false : true) : false;
        $access = Yii::app()->user->checkAccess('changeArticle', array('article_id' => $_GET['id']));
        if(isset($_GET['id']) && $access && $statusCheck) {
        $article = Article::model()->findByPk($_GET['id']);

        /**
         * Простая проверка на доступ
         * убрал ибо есть полноценная проверка на доступ))) 13.05.12 13-57
        if ((int)$article->status < Article::COMMENTS_WAIT) {
            $this->redirect($this->createUrl('article/addfiles', array('id' => $article->id)));
        }
            */
        if (isset($_POST['submit'])) {
            //$article->status = Article::UNDER_REVISION;
            //$article->save();
            $z = $article->files;
            $files = array();
            foreach ($z as $a)
                array_push($files, $a->filename);
            print_r($files);
            $d = $article->getDirectory();
            //Zip::create_zip($files, $d);

            $zip = new ZipArchive();

            $fileName = "backup_".date('j_m_Y_h_m_s').".zip";
            echo $fileName;
            $zip = new ZipArchive;
            if ($zip->open('c2reated_by_php.zip', ZipArchive::CREATE) === true){
                $zip->addFromString('ok.txt', 'данные файла');
                //$zip->addEmptyDir('name');
                $zip->close();
            }else{
                echo 'Не могу создать архив!';
            }

            //$this->redirect($this->createUrl('site/index', array('id' => $article->id)));
        }

        $this->render('confirm', array('model' => $article));
        } else {
            Yii::app()->user->setFlash('addcoauthors','У вас нет доступа к данным.');
            $this->render('confirm');
        }
    }

    public function loadModel($id) {
        $model = Article::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404,'The requested page does not exist.');

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