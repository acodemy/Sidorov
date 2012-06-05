<?php

/**
 * This is the model class for table "revisions".
 *
 * The followings are the available columns in table 'revisions':
 * @property string $id
 * @property string $user_id
 * @property string $article_id
 * @property string $comment
 * @property string $status
 * @property string $is_positive
 *
 * The followings are the available model relations:
 * @property FilesRevisions[] $filesRevisions
 * @property Articles $article
 */
class Revision extends CActiveRecord
{
    const WRITING_WAIT = 1;
    const MODERATE = 2;
    const CONFIRMED = 3;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Revision the static model class
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
			array('user_id', 'required'),
			array('user_id', 'length', 'max'=>10),
			array('comment', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('id, user_id, article_id, comment, status, is_positive', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'article' => array(self::BELONGS_TO, 'Article', 'article_id'),
            'wait' => array(self::STAT, 'Revision', 'id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Код',
			'user_id' => 'Рецензент',
			'comment' => 'Комментарий для редактора',
			'status' => 'Статус',
			'is_positive' => 'Тип',
            'authorFullName' => 'Автор'
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('article_id',$this->article_id,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('is_positive',$this->is_positive,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function getNameStatus($number)
    {
        $positive = array(self::WRITING_WAIT =>  'Ожидает написания', self::MODERATE => 'На проверке', self::CONFIRMED => 'Одобрено');
        return $positive[$number];
    }
}