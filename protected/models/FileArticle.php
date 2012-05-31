<?php

/**
 * This is the model class for table "files_articles".
 *
 * The followings are the available columns in table 'files_articles':
 * @property string $id
 * @property string $filename
 * @property string $title
 * @property string $article_id
 *
 * The followings are the available model relations:
 * @property Articles $article
 */
class FileArticle extends CActiveRecord
{
	private $_directory;

    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FileArticle the static model class
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
		return 'files_articles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('id, filename, title, article_id', 'safe', 'on'=>'search'),
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

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'filename' => 'Имя файла',
			'title' => 'Название',
            'file' => 'Добавить файл',
		);
	}

    public function beforeDelete () {
        parent::beforeDelete();
        $filename = Article::model()->findByPk($this->article_id)->getDirectoryPath() . $this->filename;

        if(!unlink($filename))
            return false;
        else
            return true;
    }

    public function afterDelete () {
        parent::afterDelete();
        $article = Article::model()->findByPk($this->article_id);

        if (!$article->filesCount) {
            $article->status = Article::FILES_WAIT;
            $article->save();
        }

        return true;
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
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('article_id',$this->article_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}