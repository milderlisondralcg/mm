<?php

class Media extends Database{

	public $conn;
	protected $view_products_by_categories = "view_products_by_categories";
	protected $view_app_codes = "mm_app_codes";
	protected $media = "mm_media";
	protected $media_attributes = "mm_media_attributes";
	
	function __construct(){
		$this->conn = parent::__construct(); // get db connection from Database model

		//print __CLASS__;
	}
	
	public function log_action($data){
		parent::log_admin_action($data);
	}
	
	
	/**
	* Get Media with give id
	* @param integer $id
	*/
	public function get($id){
		foreach($this->conn->query("SELECT * from `" . $this->view_products_by_categories . "` WHERE `ID` = '".$id."'" ) as $row) {
			//print_r($row);
		}		
	}
	/**
	* Add New Media Asset
	* There needs to be record created for each attribute that is given
	* Example: Title, Description, and Asset is given. There will be 3 records
	* @param array $data
	*/
	public function add($data){
		extract($data);
		$stmt = $this->conn->prepare("INSERT INTO `".$this->media."` (Title,Description,Type) VALUES (:Title, :Description, :Type)");
		$stmt->bindParam(':Title',$title, PDO::PARAM_STR);
		$stmt->bindParam(':Description',$description, PDO::PARAM_STR);
		$stmt->bindParam(':Type',$type, PDO::PARAM_STR);
		
		$title =  $data['Title'];
		$description = $data['Description'];
		$type = $data['Type'];
		//$user = $data['user'];
		if($stmt->execute()){
			$media_id = $this->conn->lastInsertId();
			// call method to add attributes of given media
			// pass the array of attribute values
			// add the new media id
			$data['ID'] = $media_id;
			unset($data['Title']);
			unset($data['Description']);
			unset($data['Type']);
			$this->add_media_attributes($data);
			return true;
			}else{ return false; }		
	}

	/**
	* Add the attributes associated with Media
	* $data will include the ID of Media from General table
	* @param array $data
	*
	*/
	private function add_media_attributes($data){
		print_r($data);
	}
	
	/**
	* Update Document/Asset
	* @param array $data
	* @return integer $return
	*/
	public function update(){
		
	}
	
	/**
	* Delete Document/Asset with given document id
	* @param integer $id
	* @return integer $return
	*/
	public function delete(){
		
	}
	
	/**
	* get_app_codes
	* Retrieve all app codes
	* @return array $result 
	*/
	public function get_app_codes(){
		$stmt = $this->conn->prepare("SELECT * FROM `".$this->view_app_codes."`");
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	
	
	
}