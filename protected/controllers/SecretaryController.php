<?php

class SecretaryController extends Controller
{
	public function actionIndex()
	{
        /**
         * Название раздела
         */
        $this->title = 'Статьи на проверке';

        $access = Yii::app()->user->checkAccess('viewAllArticles');

        if($access) {
		$dataProvider = new CActiveDataProvider('Article', array(
                'criteria' => array(
                    'condition'=>'status=' . Article::UNDER_REVISION
                ),
            )
        );
        $this->render('index', array('dataProvider' => $dataProvider));
        } else
        {
            Yii::app()->user->setFlash('index','У вас нет прав доступа к этой странице.');
            $this->render('index');
        }
	}


    public function actionArticle()
    {
        /*if (isset($_GET['id']) && Yii::app()->user->checkAccess('secretary'))
        {*/
            $id = (int)$_GET['id'];
            $revision = new Revisions;
            $users = new User;
            $dataProvider=new CActiveDataProvider('Article', array(
                    'criteria' => array(
                        'condition'=>'id=' . $_GET['id']
                    ),

                )
            );

            if (isset($_POST['User'])) {
                $revision->article_id = $_GET['id'];
                $revision->user_id = $_POST['User']['login'];
                $revision->is_positive=0;
                $revision->status=1;
                $revision->save();
            }

            $revisionsDP = new CActiveDataProvider('Revision', array(
                    'criteria' => array(
                        'with' => 'user',
                        'condition' => 'article_id=' . $id,
                        'order' => 't.id DESC',
                    ),
                )
            );

            $allUsers = CHtml::listData($users->model()->findAll(),'id','login');

            $this->render('article', array(
                'model' => $users,
                'dataProvider' => $dataProvider,
                'revisionsDP' => $revisionsDP,
                'users' => $allUsers,
                'article' => Article::model()->findByPk($_GET['id'])
                )
            );
       /* } else {
            Yii::app()->user->setFlash('article','У вас нет доступа к данным.');
            $this->render('article');
        }*/
    }

    public function actionRevision()
    {
        /**
         * Название раздела
         */
        $this->title = 'Рецензии на проверке';

        $access = Yii::app()->user->checkAccess('viewAllRevisions');

        if($access) {
            $dataProvider = new CActiveDataProvider('Revision', array(
                    'criteria' => array(
                        'condition'=>'status=' . Revision::MODERATE
                    ),

                )
            );
            $this->render('revision', array('dataProvider' => $dataProvider));
        } else
        {
            Yii::app()->user->setFlash('revision','У вас нет прав доступа к этой странице.');
            $this->render('revision');
        }
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