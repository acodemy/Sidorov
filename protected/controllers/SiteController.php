<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */


    public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{

		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{

                $headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));


	}

    /**
     * Отображает форму регистрации
     */
    public function actionRegister()
    {
       $model=new RegForm();

        if(isset($_POST['RegForm']))
        {
            $model->attributes=$_POST['RegForm'];
            if($model->validate())
            {
                $model->register($model->attributes);
                Yii::app()->user->setFlash('register','Спасибо за регистрацию. Мы вам отправили на почту письмо с регистрационными данными.');
                $this->refresh();
            } else
            {
                Yii::app()->user->setFlash('register','Произошла неизвестная ошибка!.');
                $this->refresh();
            }
        }
        $this->render('register',array('model'=>$model));
    }

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{

        $model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));

	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

    public function actionCreateRoles()
    {
        $auth=Yii::app()->authManager;
        $auth->clearAll();
        $auth->CreateOperation("createArticle",'создание статьи');
        $auth->CreateOperation("editArticle",'редкатирование статьи');
        $auth->CreateOperation("deleteArticle",'удаление статьи статьи');
        $auth->CreateOperation("useArticle",'манипуляции со статьиями');

        $bizRule='return Yii::app()->user->id == $params["user"];';
        $task = $auth->createTask('seeForArticle', 'просмотреть свои статьи', $bizRule);
        $task->addChild('useArticle');

        $bizRule='return Article::model()->findByPK($params["article_id"])->user_id == Yii::app()->user->id; ';
        $task = $auth->createTask('changeArticle', 'изменять/добавлять статьи', $bizRule);
        $task->addChild('useArticle');

        $role = $auth->createRole('guest');

        $role = $auth->createRole('author');
        $role->addChild('guest');
        $role->addChild('createArticle');
        $role->addChild('editArticle');
        $role->addChild('deleteArticle');
        $role->addChild('useArticle');
        $role->addChild('seeForArticle');
        $role->addChild('changeArticle');

        $role = $auth->createRole('secretary');
        $role->addChild('author');

        $role = $auth->createRole('chief');
        $role->addChild('secretary');
        $role->addChild('editArticle');
        $auth->assign('author', 10);



    }
}