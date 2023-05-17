

<?php

class users extends database {

    // Properties
    public $user_id;
    public $user_email;
    public $user_password;
    public $created_at;


    public function __construct()
    {
        parent::__construct();
    }

    public function register_user()
    {
        $statement = "INSERT INTO user (email,password,created_at) VALUES(:email,:password,:created_at)";

        $param = [
            ':email' => $this->user_email,
            ':password' => $this->user_password,
            ':created_at' => $this->created_at
        ];

        if ($this->insert($statement,$param)=== 'yey'){
            return 'registered';
        }else{
            return 'na lie';
        }
        
    }

    public function get_users()
    {
        $statement = "SELECT * FROM user WHERE id=:id";
        $param = [
            'id'=>$this->user_id
        ];
        $execute = $this->select($statement,$param);

        return $execute;
    }
}