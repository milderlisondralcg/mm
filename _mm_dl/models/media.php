<?php

class Media extends Database{

	public $conn;
	protected $media = "mm_media";	
	
	function __construct(){
		$this->conn = parent::__construct(); // get db connection from Database model
	}
	
	/**
	* @param array $data (user,action,object,previous_data,update_data)
	*/
	public function log_action($data){
		parent::log_admin_action($data);
	}
	
	
	/**
	* Get Media with given url ( SeoUrl )
	* @param string $seo_url
	*/
	public function get_media_by_url($seo_url){
		$query = "SELECT * FROM `".$this->media."` WHERE `SeoUrl`=:SeoUrl";
		$stmt = $this->conn->prepare($query);
		$stmt->bindValue(':SeoUrl',$seo_url, PDO::PARAM_STR);		
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if( count($result) > 0 ){
			return $result;
		}else{
			return 0;
		}
	}	
	
	
}