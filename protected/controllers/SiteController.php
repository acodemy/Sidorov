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
    public function actionMain()
    {
        $this->title = 'Личный кабинет';

        if(!Yii::app()->user->isGuest) {
            $uid = Yii::app()->user->id;
            $model = array();

            $criteria = new CDbCriteria();
            $criteria->select = 'id';
            $criteria->condition ='user_id=:userID and status=:statusID';
            $statusArray = Article::getStatusArray();

            foreach($statusArray as $key => $value)
            {
                $criteria->params=array(':userID' => (int) Yii::app()->user->id, 'statusID' => $value);
                $model[$key]=Article::model()->count($criteria); // $params не требуется
                $model['user'] = Yii::app()->user->id;
            }

            $cr = new CDbCriteria();
            $cr->select = 'id';
            $cr->condition = 'user_id=' . $uid . ' AND status=:status';
            $cr->params = array(':status' => Revision::WRITING_WAIT);
            $revisions['wait'] = Revision::model()->count($cr);

            $cr->condition = 'user_id=' . $uid . ' AND status>1';
            $revisions['all'] = Revision::model()->count($cr);


            $this->render('main',array('model'=>$model, 'revisions' => $revisions));
        } else {
            throw new CHttpException(404,'У вас нет доступа к данным.');
        }

    }

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
        $this->title = 'Контакты';

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
        $this->title = 'Регистрация';

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
        $this->render('register', array('model' => $model, 'title' => $this->title));
    }

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
        $this->title = 'Форма входа';

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

    /**
     * Удалить метод в релизе
     */
    public function actionCreateRoles()
    {
        /**
         * Инициализация authManager
         */
        $auth = Yii::app()->authManager;
        $auth->clearAll();

        /**
         * Операции для автора
         */
        $auth->createOperation('createArticle', 'Cоздание статьи');
        $bizRule = 'return Yii::app()->user->id == $params["article"]->user_id;';
        $auth->createOperation('ownArticle', 'Работа со своими статьями', $bizRule);
        $auth->createOperation('viewOwnArticles', 'Просмотр своих ');

        /**
         * Операции для рецензента
         */
        $bizRule = 'return Yii::app()->user->id == $params["revison"]->user_id;';
        $auth->createOperation('addRevision', 'Добавление рецензии', $bizRule);
        $bizRule = 'return Yii::app()->user->id == $params["revison"]->user_id;';
        $auth->createOperation('viewOwnRevisions', 'Просмотр своих рецензии', $bizRule);

        /**
         * Операции для секретаря
         */
        $auth->createOperation('moderateArticle', 'Проверка статьи');
        $auth->createOperation('moderateRevision', 'Проверка рецензии');
        $auth->createOperation('addRevisor', 'Добавление рецензента');
        $auth->createOperation('viewAllArticles', 'Просмотр статей');
        $auth->createOperation('viewAllRevisions', 'Просмотр рецензий');

        /**
         * Назначение ролей
         */
        $role = $auth->createRole('author');
        $role->addChild('createArticle');
        $role->addChild('ownArticle');
        $role->addChild('viewOwnArticles');

        $role = $auth->createRole('revisor');
        $role->addChild('author');
        $role->addChild('addRevision');
        $role->addChild('viewOwnRevisions');

        $role = $auth->createRole('secretary');
        $role->addChild('revisor');
        $role->addChild('moderateArticle');
        $role->addChild('moderateRevision');
        $role->addChild('addRevisor');
        $role->addChild('viewAllArticles');
        $role->addChild('viewAllRevisions');

        $role = $auth->createRole('chief');
        $role->addChild('secretary');

        $auth->assign('chief', 1);
        $auth->assign('author', 10);
        $auth->assign('revisor', 11);
    }
}