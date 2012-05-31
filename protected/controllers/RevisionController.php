<?php

class RevisionController extends Controller
{
	public function actionAdd() {

        $revision = Revision::model()->findByPk($_GET['id']);//$this->loadModel($_GET['id'], 'Revision');
        $isNotGuest = true; //Yii::app()->user->checkAccess('changeArticle', array('article_id' => $_GET['id']));


        if($isNotGuest) {
            if (isset($_POST['Revision']) && !empty($_FILES)) {
                $filesPath = Article::model()->findByPk($revision->article_id)->getDirectoryPath();
                $revision->attributes = $_POST['Revision'];
                $upload = new Upload(str_replace('//', '/', $filesPath));

                if ($upload->uploads($_FILES['file'])) {
                    $fileInfo = $upload->getFilesInfo();
                    $revision->title = $fileInfo['name'];
                    $revision->filename = $fileInfo['nameTranslit'];

                    if($revision->validate()) {
                        $revision->status = Revision::MODERATE;
                        $revision->save();

                        $this->redirect($this->createUrl('site/main'));
                    }
                }
            }
            $this->render('add', array('model' => $revision));
        } else {
            Yii::app()->user->setFlash('browsing','У вас нет доступа к данным.');
            $this->render('browsing');
        }
	}






	public function actionIndex()
	{
		$this->render('index');
	}

    public function actionArticlesList () {
        $this->render('articleslist');
    }

    public function actionList () {
        $this->render('list');
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