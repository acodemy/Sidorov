<?php

class RevisionController extends Controller
{
	public function actionAdd() {
        /**
         * Название раздела
         */
        $this->title = 'Добавление рецензии';

        $revision = $this->loadModel($_GET['id']);
        $access = Yii::app()->user->checkAccess('addRevision', array('revision' => $revision));


        if($access) {
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
            $this->render('add', array('revision' => $revision));
        } else {
            Yii::app()->user->setFlash('add','У вас нет доступа к данным.');
            $this->render('add');
        }
	}

    public function actionAction () {
        if(Yii::app()->request->isPostRequest) {
            $revision = $this->loadModel($_GET['id']);
            switch ($_GET['action']) {
                case 'approve':
                    $revision->status = Revision::APPROVED;
                    $revision->save();
                    break;
                case 'disapprove':
                    $revision->status = Revision::DISAPPROVED;
                    $revision->save();
                    break;
                case 'delete':
                    $revision->delete();
                    break;
            }

            if(!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400,'Неверный запрос. Пожалуйста, не пытайтесь его повторить.');
        }
    }

    public function loadModel($id) {
        $model = Revision::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
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