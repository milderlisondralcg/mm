<?php

class Media extends Database{

	public $conn;
	protected $view_products_by_categories = "view_products_by_categories";
	protected $view_app_codes = "mm_app_codes";
	protected $media = "mm_media";
	protected $media_attributes = "mm_media_attributes";
	protected $media_tags = "mm_media_tags";
	
	function __construct(){
		$this->conn = parent::__construct(); // get db connection from Database model

		//print __CLASS__;
	}
	
	/**
	* @param array $data (user,action,object,previous_data,update_data)
	*/
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
	* @return array $result
	*/
	public function add($data){
		extract($data);
		$stmt = $this->conn->prepare("INSERT INTO `".$this->media."` (Title,Description,Type,Saved_Media) VALUES (:Title, :Description, :Type, :Saved_Media)");
		$stmt->bindParam(':Title',$title, PDO::PARAM_STR);
		$stmt->bindParam(':Description',$description, PDO::PARAM_STR);
		$stmt->bindParam(':Type',$type, PDO::PARAM_STR);
		$stmt->bindParam(':Saved_Media',$saved_media, PDO::PARAM_STR);
		
		$title =  $data['Title'];
		$description = $data['Description'];
		$type = $data['Type'];
		$saved_media = $data['saved_media'];

		if($stmt->execute()){
			$data['ID'] = $this->conn->lastInsertId();
			$this->add_media_tags($data); // add tags for given media
			$media_attribs = array("ID"=>$data['ID'],"attributes"=>$data);
			$this->add_media_attributes($media_attribs); // add attributes of given media
			$result = array("ID"=>$data['ID'],"result"=>true);
			return $result;
			}else{ return false; }		
	}

	/**
	* Add the attributes associated with Media
	* $data will include the ID of Media from General table
	* @param array $data
	*
	*/
	private function add_media_attributes($data){
		unset($data['attributes']['ID']);
		unset($data['attributes']['Tags']);
		unset($data['attributes']['saved_media']);
		unset($data['attributes']['Title']);
		unset($data['attributes']['Description']);
		unset($data['attributes']['Type']);
		foreach($data['attributes'] as $key=>$value){
			$stmt = $this->conn->prepare("INSERT INTO `".$this->media_attributes."` (Media_ID,Attribute, Attribute_Value) VALUES (:Media_ID, :Attribute, :Attribute_Value)");
			$stmt->bindParam(':Media_ID',$media_id, PDO::PARAM_INT);
			$stmt->bindParam(':Attribute',$attribute, PDO::PARAM_STR);
			$stmt->bindParam(':Attribute_Value',$attribute_value, PDO::PARAM_STR);
			
			$media_id =  $data['ID'];
			$attribute = $key;
			$attribute_value = $value;
			$stmt->execute();
		}		
	}
	
	/**
	* Add tags for Media
	* @param array $media
	*/
	private function add_media_tags($data){
		$tags_array = explode(" ",$data['Tags']);
		foreach($tags_array as $value){
			$stmt = $this->conn->prepare("INSERT INTO `".$this->media_tags."` (Media_ID,Tag) VALUES (:Media_ID, :Tag)");
			$stmt->bindParam(':Media_ID',$media_id, PDO::PARAM_INT);
			$stmt->bindParam(':Tag',$tag, PDO::PARAM_STR);
			
			$media_id =  $data['ID'];
			$tag =  $value;
			$stmt->execute();
		}
	}
	
	/**
	* Update Document/Asset
	* @param array $data ( field = field to be updated; field_value = new value of field )
	* @return integer $return
	*/
	public function update($data){ print_r($data);
		extract($data); 
		$stmt = $this->conn->prepare("UPDATE `".$this->media."` SET `Saved_Media`=:Saved_Media WHERE `Id`=:Id");
		$stmt->bindParam(':Saved_Media',$saved_media, PDO::PARAM_STR);
		$stmt->bindParam(':Id',$id, PDO::PARAM_INT);
		
		$id =  $data['id'];
		$saved_media = $data['saved_media'];
		
		$stmt->execute();
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