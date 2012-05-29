<?php
/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */

class RegForm extends CFormModel
{
    public $first_name;
    public $middle_name;
    public $last_name;
    public $degree;
    public $phone;
    public $additional_phone;
    public $email;
    public $country;
    public $city;
    public $address;
    public $position;   //должность
    public $institution;
    public $department;
    public $login;
    public $password;
    public $pass2;
    public $may_reviewer;
    //public $type;


    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('login, password, email, first_name, last_name, country, city, degree, phone, position, institution, may_reviewer, department', 'required'),
            array('login, password', 'length', 'max'=>32),
            array('email, first_name, middle_name, last_name, country, city, degree, phone, additional_phone, department', 'length', 'max'=>64),
            array('address, position, institution', 'length', 'max'=>256),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            //array('id, login, password, email, usertype_id, first_name, middle_name, last_name, country, city, degree, phone, additional_phone, address, position, institution, departament, may_reviewer', 'safe', 'on'=>'search'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */

    public function register($attrs)
    {
        $user = new User;
       // print_r($attrs);
        foreach ($attrs as $key => $value)
        {
            if($key != 'pass2')
            {
                $user->$key = $value;
            };
        }
        $user->password = $user->hashPassword($user->password);

        $user->save();
        AuthAssignment::makeRoleAuthor($user->id);      //добавил к новому зарегестрированному права автора

        return true;
    }

    public function attributeLabels()
    {
        return array(
            'verifyCode'=>'Verification Code',
        );
    }
}