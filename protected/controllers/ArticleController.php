<?php

class ArticleController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
        $this->addArticle();

	}

    public function addArticle () {

        $article = new Article();
        if (isset($_POST['Article'])) {
            // получаем данные от пользователя
            $article->attributes=$_POST['Article'];
            // проверяем полученные данные и, если результат проверки положительный,
            // перенаправляем пользователя на предыдущую страницу
           // if($article->validate())
                //$this->redirect(Yii::app()->article->returnUrl);
        }
        $article->user_id = Yii::app()->user->id;
        $article->save();

        // рендерим представление
        //$this->render('login',array('model'=> $article));
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