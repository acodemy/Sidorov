<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $usertype_id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $country
 * @property string $city
 * @property string $degree
 * @property string $phone
 * @property string $additional_phone
 * @property string $address
 * @property string $position
 * @property string $institution
 * @property string $department
 * @property string $may_reviewer
 *
 * The followings are the available model relations:
 * @property Articles[] $articles
 * @property Usertypes $usertype
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, password, email, first_name, last_name, country, city, degree, phone, position, institution, department, may_reviewer', 'required'),
			array('id', 'length', 'max'=>10),
			array('login, password', 'length', 'max'=>32),
			array('email, first_name, middle_name, last_name, country, city, degree, phone, additional_phone', 'length', 'max'=>64),
			array('usertype_id, may_reviewer', 'length', 'max'=>1),
			array('address, position, institution, department', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, login, password, email, usertype_id, first_name, middle_name, last_name, country, city, degree, phone, additional_phone, address, position, institution, department, may_reviewer', 'safe', 'on'=>'search'),
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
			'articles' => array(self::HAS_MANY, 'Articles', 'user_id'),
			'usertype' => array(self::BELONGS_TO, 'Usertypes', 'usertype_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'Login',
			'password' => 'Password',
			'email' => 'Email',
			'usertype_id' => 'Usertype',
			'first_name' => 'First Name',
			'middle_name' => 'Middle Name',
			'last_name' => 'Last Name',
			'country' => 'Country',
			'city' => 'City',
			'degree' => 'Degree',
			'phone' => 'Phone',
			'additional_phone' => 'Additional Phone',
			'address' => 'Address',
			'position' => 'Position',
			'institution' => 'Institution',
			'department' => 'department',
			'may_reviewer' => 'May Reviewer',
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
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('usertype_id',$this->usertype_id,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('middle_name',$this->middle_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('degree',$this->degree,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('additional_phone',$this->additional_phone,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('institution',$this->institution,true);
		$criteria->compare('department',$this->department,true);
		$criteria->compare('may_reviewer',$this->may_reviewer,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function validatePassword ($password) {
        return $this->hashPassword($password) === $this->password;
    }

    public function hashPassword ($password) {
        return md5(md5($password));
    }

    public function getFullName () {
        $mn = (!empty($this->middle_name)) ? mb_substr($this->middle_name, 0, 2) . '.' : '';
        return $this->last_name . ' ' . mb_substr($this->first_name, 0, 2) . '. ' . $mn;
    }
}