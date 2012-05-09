<?php

class ArticleController extends Controller
{


    public $_status = array ("REJECTED" => 1, "PUBLISHED" => 2, "UNDER_REVISION"=> 3, "COAUTHORS_WAIT" => 4, "FILES_WAIT" => 5, "COMMENTS_WAIT" => 6, "CONFIRM_WAIT" => 7, "REWORK" => 8);

    public static function return_name_status($i)
    {
        switch ($i) {
            case 1:
                return "submit";
                break;
            case 2:
                return "submit";
                break;
            case 3:
                return "submit";
                break;
            case 4:
                return "addcoauthors";
                break;
            case 5:
                return "addfiles";
                break;
            case 6:
                return "addcomment";
                break;
            case 7:
                return "confirm";
                break;
            case 8:
                return "submit";
                break;
        }

    }

    public static function get_name_status($i)
    {
    switch ($i) {
    case 1:
        echo "Отклоненные";
        break;
    case 2:
        echo "Опубликованные";
        break;
    case 3:
        echo "Ожидающие рецензию";
        break;
    case 4:
        echo "С недобавленными авторами";
        break;
    case 5:
        echo "Ожидающие добавления файлов";
        break;
    case 6:
        echo "Ожидаюющие камментария";
        break;
    case 7:
        echo "Ожидаюющие отправки";
        break;
    case 8:
        echo "На доработке";
        break;
    }
    }
	public function actionIndex()
	{
        if(!Yii::app()->user->isGuest)
        {
        // Тут должна быть менюшка со списком действий, применительно к статье
        $article = new Article();
        $model = array();
        $criteria=new CDbCriteria;
        $criteria->select='id';
        $criteria->condition='user_id=:userID and status=:statusID';
        foreach($this->_status as $key => $value)
        {
            $criteria->params=array(':userID' => (int) Yii::app()->user->id, 'statusID' => $value);
            $model[$key]=Article::model()->count($criteria); // $params не требуется
            $model['user'] =  Yii::app()->user->id;
        }
        } else {
            Yii::app()->user->setFlash('contact','У вас нет доступа к данным.');
        }
       $this->render('index',array('model'=>$model));

	}


    public function actionBrowsing()
    {
        if(Yii::app()->user->checkAccess('seeForArticle', array('user' => $_GET['user_id'])))
        {
        $article = new Article();
        $model = array("user" => (int) $_GET['user_id'], "status" => (int) $_GET['status_id']);
        $model['dataProvider'] = new CActiveDataProvider('Article', array(
            'criteria'=>array(
                'condition'=>'user_id =:User AND status =:Status',
                'params'=>array(':User' => $model['user'], ':Status' => $model['status'])
            ),
        ));

       /* $criteria=new CDbCriteria();
        $criteria->condition='user_id=:userID and status=:statusID';
        $criteria->params=array(':userID' => (int) $_GET['user_id'], 'statusID' => (int) $_GET['status_id']);
        $post=Article::model()->findAll($criteria); // $params не требуется
        foreach($post as $title)
            {
             $model[] = $title;
            }

        */
        } else {
            Yii::app()->user->setFlash('contact','У вас нет доступа к данным.');
        }
        $this->render('browsing',array('model'=>$model));
    }



    public function actionSubmit () {
        if(Yii::app()->user->checkAccess('changeArticle', array('article_id' => isset($_GET['id']) ? $_GET['id'] : Yii::app()->user->id)))
        {

        $article = new Article();

        if (isset($_POST['Article'])) {
            if(isset($_POST['Article']['id']))
            {
                $article=Article::model()->findByPk($_POST['Article']['id']);
            }

            $article->attributes=$_POST['Article'];

            if($article->validate()) {
                $article->user_id = Yii::app()->user->id;
                $article->save();

                $this->redirect($this->createUrl('article/addcoauthors', array('id' => $article->id)));
            }
        }

        $this_id = isset($_GET['id']) ? $_GET['id'] : -1;

        $dProvider = new CActiveDataProvider('Article', array(
            'criteria'=>array(
                'condition'=>'user_id =:User AND id =:id',
                'params'=>array(':User' => Yii::app()->user->id, ':id' => $this_id)
            ),
        ));
        } else {
            Yii::app()->user->setFlash('contact','У вас нет доступа к данным.');
        }

        $this->render('submit',array('model'=> array('Article' => $article, 'dataProvider' => $dProvider, 'id' => $this_id)));

    }



    public function actionAddCoauthors () {
        if(isset($_GET['id']) & Yii::app()->user->checkAccess('changeArticle', array('article_id' => isset($_GET['id']) ? $_GET['id'] : Yii::app()->user->id)))
        {

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
        } else {
            Yii::app()->user->setFlash('contact','У вас нет доступа к данным.');
        }

       $this->render('addcoauthors',array('model'=>'Article'));

    }

    public function actionAddFiles () {
        /**
         * Создание объекта статьи, с которой происходит работа
         */
        if(isset($_GET['id']) & Yii::app()->user->checkAccess('changeArticle', array('article_id' => isset($_GET['id']) ? $_GET['id'] : Yii::app()->user->id)))
        {

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
        } else {
            Yii::app()->user->setFlash('contact','У вас нет доступа к данным.');
        }
       $this->render('addfiles',array('model'=> $article));
    }

    public function actionAddComment () {
        /**
         * Создание объекта статьи, с которой происходит работа
         */
        if(isset($_GET['id']) & Yii::app()->user->checkAccess('changeArticle', array('article_id' => isset($_GET['id']) ? $_GET['id'] : Yii::app()->user->id)))
        {
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
        } else {
            Yii::app()->user->setFlash('contact','У вас нет доступа к данным.');
        }
        $this->render('addcomment',array('model'=> $article));
    }

    public function actionConfirm () {
        /**
         * Создание объекта статьи, с которой происходит работа
         */
        if(isset($_GET['id']) & Yii::app()->user->checkAccess('changeArticle', array('article_id' => isset($_GET['id']) ? $_GET['id'] : Yii::app()->user->id)))
        {
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
        } else {
            Yii::app()->user->setFlash('contact','У вас нет доступа к данным.');
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