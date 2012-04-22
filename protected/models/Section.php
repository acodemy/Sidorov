<?php

/**
 * This is the model class for table "sections".
 *
 * The followings are the available columns in table 'sections':
 * @property string $id
 * @property string $parent_id
 * @property string $name
 * @property string $udk
 *
 * The followings are the available model relations:
 * @property Articles[] $articles
 */
class Section extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Section the static model class
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
		return 'sections';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('udk', 'required'),
			array('parent_id, udk', 'length', 'max'=>10),
			array('name', 'length', 'max'=>128),
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
			'articles' => array(self::HAS_MANY, 'Article', 'section_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Название раздела',
			'udk' => 'УДК',
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
		$criteria->compare('parent_id',$this->parent_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('udk',$this->udk,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function showAll () {
        $sections = $this->findAll();
        foreach ($sections as $s) {
            $formattedUdk = vsprintf("%05s", $s->udk);
            $listOfSections[$s->id] = $formattedUdk . ' - ' . $s->name;
        }

        return $listOfSections;
    }
}