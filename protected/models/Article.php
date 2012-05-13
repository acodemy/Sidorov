<?php

/**
 * This is the model class for table "articles".
 *
 * The followings are the available columns in table 'articles':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $comment
 * @property integer $status
 * @property string $user_id
 * @property string $section_id
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Sections $sectionUdk
 * @property FilesArticles[] $filesArticles
 * @property Revisions[] $revisions
 */
class Article extends CActiveRecord
{
    /**
     * Статусы статьи
     */
    const REJECTED = 1;
    const PUBLISHED = 2;
    const UNDER_REVISION = 3;
    const COAUTHORS_WAIT = 4;
    const FILES_WAIT = 5;
    const COMMENTS_WAIT = 6;
    const CONFIRM_WAIT = 7;
    const REWORK = 8;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Article the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'articles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description, section_id', 'required'),
			array('title', 'length', 'max'=>255),
			array('status', 'length', 'max'=>1),
			array('comment', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user_id' => array(self::BELONGS_TO, 'User', 'id'),
			'section_id' => array(self::BELONGS_TO, 'Section', 'section_id'),
			'files' => array(self::HAS_MANY, 'FilesArticle', 'article_id'),
			'revisions' => array(self::HAS_MANY, 'Revision', 'article_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'title' => 'Название статьи',
			'description' => 'Описание',
			'comment' => 'Комментарий',
			'section_id' => 'Раздел',
		);
	}

    /**
     * Проверка наличия добавленных файлов к статье
     * @return bool
     */
    public function hasFiles () {
        return (bool) FileArticle::model()->countByAttributes(array('article_id' => $this->id));
    }

    /**
     * Получение пути к папке с файлами для текущей статьи.
     * Имя папки генерируется по специальному алгоритму
     * @return string
     */
    public function getDirectory () {
        return dirname($_SERVER['SCRIPT_FILENAME']) . '/files/' . substr(md5($this->id), 0, 8) . '/';
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('section_id',$this->section_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getStatusArray () {
        return array('REJECTED' => 1, 'PUBLISHED' => 2, 'UNDER_REVISION' => 3, 'COAUTHORS_WAIT' => 4,
                           'FILES_WAIT' => 5, 'COMMENTS_WAIT' => 6, 'CONFIRM_WAIT' => 7,'REWORK' => 8);
    }

    public static function getNameStatus($i) {
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
        public static function returnNameStatus($i)
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

    public static function getStatusAuthor($i) {
        switch ($i) {
            case 3:
                return "Изменить основные данные";
                break;
            case 4:
                return "Добавить/изменить авторов";
                break;
            case 5:
                return "Добавить/удалить файлы к статье";
                break;
            case 6:
                return "Добавить/изменить комментарий";
                break;
            case 7:
                return "Отправить статью на рассмотрение";
                break;
        }
    }


    // изменяет статус статьи на этап $delta (параметр необязателен, по умолчанию переводит на 1 эта выше
    public function statusUpdate($status, $table, $delta = null)
    {
        if ($table->status == $status)
        {
            $table->status = ($delta != null) ? $delta : ++$table->status;
            $table->save();
        }
    }


}