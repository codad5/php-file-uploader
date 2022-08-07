<?php

    class Dbh{
        private $host= "localhost";
        private $user= "root";
        private $pwd= "";
        private $dbName= "wemall";

        public function __construct(){
            try{
                $this->connect();
                
            }catch(PDOException $e){
                
                $message = "SQLSTATE[HY000] [1049] Unknown database '".$this->dbName."'";
                echo $e->getMessage() === $message;
                
                if($e->getMessage() === $message){
                    
                    // $this>prepareDbConnection();
                    $conn = new mysqli($this->host, $this->user, $this->pwd);
                        // Check connection     
                    
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        
                        // Create database
                        $sql = "CREATE DATABASE ".$this->dbName.";";
                        
                    
                        if ($conn->query($sql) === TRUE) {
                           
                            
                        
                        $this->initializeDb();
                        } else {
                            
                            
                        
                            exit();
                        }

                        $conn->close();

                }else {
                        
                            exit();
                        }
                

            }
        }

        protected function initializeDb(){
        require_once "sql.php";
        $stmt = $this->connect()->prepare($setup_sql);
        if(!$stmt->execute(array())){
            $stmt = null;
            return false;

        }
    }

    public function attachDb(){
        return $this->connect();
    }
        protected function connect() {
            $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbName;
            $pdo = new PDO($dsn, $this->user, $this->pwd);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        }

        public function check_account($email){
        $stmt = $this->connect()->prepare("SELECT * FROM admins WHERE email = ?;");
        if(!$stmt->execute(array($email))){
            $stmt = null;
            return 'stmt Error';
            exit();

        }
        // return $stmt->rowCount();
        if($stmt->rowCount() > 0){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
        else{
            return  true; 
            #$stmt->rowCount();
        }
       

    }
    
    
    public function getOrders(){
        $sql = 'SELECT * FROM orders JOIN  WHERE';
    }
    public function getProduct($refrence = null, $bol = false){
        $additional_sql = "WHERE active_status <> 'deleted' ;";
        if($bol == true){
            $additional_sql = ";";

        }
        switch ($refrence) {
            case 'value':
                # code...
                break;
                
                default:
                $sql = "SELECT * FROM products ".$additional_sql;
                # code...
                break;
        }
        $stmt = $this->connect()->prepare($sql);
        if(!$stmt->execute()){
            $stmt = null;
            return false;

        }
        // return $stmt->rowCount();
        if($stmt->rowCount() < 1){
            // echo $stmt->rowCount();
            return  false; #$stmt->rowCount();
            
        }
        else{
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            // return false;
        }
       

    }
    
    
    }