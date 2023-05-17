<?php


class database {

    private $host = 'localhost';
    private $database_name = 'reseller';
    private $username = 'root';
    private $password = '';
    public $conn;
   

    public function __construct()
    {
        $dsn = 'mysql:dbname=' . $this->database_name . ';host=' . $this->host;
        
        try {
            $conn = new PDO($dsn, $this->username,$this->password);
             $this->conn = $conn;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }


    public function insert($statement_,$param=[])
    {
        $statement = $this->conn->prepare($statement_);

        $statement->execute($param);

        return 'yey';
    }


    public function select($statement_, $param = [])
    {
        $statement = $this->conn->prepare($statement_);

        $statement->execute($param);

        return $statement->fetchAll(PDO::FETCH_ASSOC,);
    }

    






}