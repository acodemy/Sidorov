<?php

/**
 * This is the model class for table "articles".
 *
 * The followings are the available columns in table 'articles':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $comment
 * @property string $status
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
}