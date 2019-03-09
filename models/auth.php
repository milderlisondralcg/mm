<?php

class Auth extends Database{

	public $conn;
	protected $members = "exp_members";
	
	function __construct(){
		$this->conn = parent::__construct(); // get db connection from Database model
	}	
	
	/**
	* Get last activity timestamp of member
	* @param int $MemberID
	* 
	*/
	public function get_last_activity($MemberID){
		$query = "SELECT `username`,`last_activity` FROM `exp_members` WHERE `member_id` =:MemberID ";
		$stmt = $this->conn->prepare($query);
		$stmt->bindValue(':MemberID',$MemberID, PDO::PARAM_INT);		
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if( count($result) > 0 ){
			return $result;
		}else{
			return 0;
		}		
	}
}