<?php

class Database{

/*  	private $db_host = "us-cdbr-azure-west-b.cleardb.com";
	private $db_name = "pocmarcomwwwcharliedb";
	private $db_user = "be34d90920856c";
	private $db_pass = "804e507c";
	 */
	public $conn;
	
	private $admin_actions = "mm_log_admin_action";
	private $mm_downloads = "mm_downloads";
	
	protected function __construct(){
		$this->conn = null;
		
		foreach ($_SERVER as $key => $value) {
			if (strpos($key, "MYSQLCONNSTR_localdb") !== 0) {
				continue;
			}
			
			$this->db_host = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
			$this->db_name = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
			$this->db_user = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
			$this->db_pass = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
		}		

        try{
            $this->conn = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_pass);
            $this->conn->exec("set names utf8");
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
	}

	/**
	* log_admin_action
	* Log action made by an admin
	* @param $data array 
	*/
	protected function log_admin_action( $data ){
		    $stmt = $this->conn->prepare("INSERT INTO `".$this->admin_actions."` (Admin, Action, Object,Previous_Data, Updated_Data) VALUES (:Admin, :Action, :Object,:Previous_Data, :Updated_Data)");
			$stmt->bindParam(':Admin', $user);
			$stmt->bindParam(':Action',$action);
			$stmt->bindParam(':Object',$object);
			$stmt->bindParam(':Previous_Data',$previous_data, PDO::PARAM_STR);
			$stmt->bindParam(':Updated_Data', $updated_data, PDO::PARAM_STR);
			
			// set values
			$user = $data['user'];
			$action = $data['action'];
			$object = $data['object'];
			$previous_data = $data['previous_data'];
			$updated_data = $data['updated_data'];
			
			$stmt->execute();			
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
