<?php

/**
 * This is the model class for table "revisions".
 *
 * The followings are the available columns in table 'revisions':
 * @property string $id
 * @property string $article_id
 * @property string $comment
 * @property string $is_positive
 * @property string $user_id
 * @property string $status
 * @property integer $filename
 * @property string $title
 *
 * The followings are the available model relations:
 * @property FilesRevisions[] $filesRevisions
 * @property Articles $article
 */
class Revisions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Revisions the static model class
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
		return 'revisions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('article_id, is_positive, user_id, status', 'required'),
			array('filename', 'numerical', 'integerOnly'=>true),
			array('article_id, user_id', 'length', 'max'=>10),
			array('is_positive, status', 'length', 'max'=>1),
			array('title', 'length', 'max'=>64),
			array('comment', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, article_id, comment, is_positive, user_id, status, filename, title', 'safe', 'on'=>'search'),
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
			'filesRevisions' => array(self::HAS_MANY, 'FilesRevisions', 'revision_id'),
			'article' => array(self::BELONGS_TO, 'Articles', 'article_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'article_id' => 'Article',
			'comment' => 'Comment',
			'is_positive' => 'Is Positive',
			'user_id' => 'User',
			'status' => 'Status',
			'filename' => 'Filename',
			'title' => 'Title',
		);
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
		$criteria->compare('article_id',$this->article_id,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('is_positive',$this->is_positive,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('filename',$this->filename);
		$criteria->compare('title',$this->title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function getNamePositive($positive)
    {
        switch ($positive)
        {
            case 1:
                return 'Одобрено';
                break;
            case 2:
                return 'Не одобрено';
                break;
            default:
                return 'Не рассмотрено';
        }
    }
}