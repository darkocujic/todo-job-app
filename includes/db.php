<?php

class Db {
	private $host = "localhost";
    private $db_name = "todo";
    private $username = "root";
    private $password = "";
    
    public $dbconn;


	public function dbConnect()
	{
     
	    $this->dbconn = null;    
        try
		{
            $this->dbconn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }
		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }
         
        return $this->dbconn;
    }

}

?>