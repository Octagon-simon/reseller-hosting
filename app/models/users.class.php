

<?php

class users extends database {

    // Properties
    public $user_id;
    public $user_fname;
    public $user_lname;
    public $user_email;
    public $user_password;

    public function __construct()
    {
        parent::__construct();
    }

    public function register_user()
    {
        $statement = "INSERT INTO users (name, email, secret, created_at) VALUES(:name, :email,:secret,:created_at)";

        $param = [
            ':name' => $this->user_lname . ' ' .$this->user_fname,
            ':email' => $this->user_email,
            ':secret' => password_hash($this->user_password, PASSWORD_BCRYPT),
            ':created_at' => time()
        ];

        if ($this->insert($statement,$param)=== 'yey'){
            return true;
        }

        return;
        
    }
    //check if a user exists
    public function user_exists()
    {
        $statement = "SELECT * FROM users WHERE email = :email";

        $param = [
            ':email' => $this->user_email
        ];

        if (!empty($this->select($statement,$param))){
            return true;
        }

        return;
        
    }

    //retrieves a single user using email
    public function get_user()
    {
        $statement = "SELECT * FROM users WHERE email = :email";
        $param = [
            'email'=>$this->user_email
        ];
        $execute = $this->select($statement,$param);

        return $execute;
    }

    public function get_users()
    {
        $statement = "SELECT * FROM users WHERE id=:id";
        $param = [
            'id'=>$this->user_id
        ];
        $execute = $this->select($statement,$param);

        return $execute;
    }

}