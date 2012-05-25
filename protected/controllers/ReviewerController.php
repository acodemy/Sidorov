<?php

class ReviewerController extends Controller
{
	 public function actionDelete () {
        if(Yii::app()->request->isPostRequest) {
            $revisions = $this->loadModel($_GET['id'], 'Revisions');

            $revisions->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionApprove () {
        if(Yii::app()->request->isPostRequest) {
            $revisions =  $this->loadModel($_GET['id'], 'Revisions');
            $revisions->positive = 1;
            $revisions->save();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }
    public function actionDisapprove () {
        if(Yii::app()->request->isPostRequest) {
            $revisions = $this->loadModel($_GET['id'], 'Revisions');;
            $revisions->positive = 2;
            $revisions->save();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function loadModel($id, $table) {
        $model = $table::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404,'The requested page does not exist.');

        return $model;
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