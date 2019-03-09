<?php

class Database{

	private $db_host = "us-cdbr-azure-west-b.cleardb.com";
	private $db_name = "prodmarcomwwwcleardbd1";
	private $db_user = "b2bc83f509ebd5";
	private $db_pass = "e31ff4b8";
	public $conn;
	
	private $admin_actions = "mm_log_admin_action";
	private $mm_downloads = "mm_downloads";
	
	protected function __construct(){
		$this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_pass);
            $this->conn->exec("set names utf8");
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
	}

	
	protected function log_dl( $data ){
			extract($data);
		    $stmt = $this->conn->prepare("INSERT INTO `".$this->mm_downloads."` (MediaID, MediaTitle, IPAddress) VALUES (:MediaID, :MediaTitle, :IPAddress)");
			$stmt->bindValue(':MediaID', $MediaID);
			$stmt->bindValue(':MediaTitle',$MediaTitle);
			$stmt->bindValue(':IPAddress',$IPAddress);
			
			$stmt->execute();			
	}

	
}
