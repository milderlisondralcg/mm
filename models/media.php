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
	* get_media_all
	* Retrieve all products
	*
	*/
	public function get_media_all(){
		$stmt = $this->conn->prepare("SELECT `MediaID`,`Title`,`Description`,`Category`,`SeoUrl`,`SavedMedia` FROM `".$this->media."`");	
		$stmt->execute();
		$all_results = $stmt->fetchAll();
		foreach( $all_results as $row ){
			extract($row);
			$results[] = array("Title"=>$row['Title'],"Category"=>$row['Category'],"Description"=>$row['Description'],"SavedMedia"=>$SavedMedia);
		}
		return $all_results;
		
	}
	
	
	/**
	* Get Media with given id
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
		if ( $this->check_title($Title) == 0 ){
			$stmt = $this->conn->prepare("INSERT INTO `".$this->media."` (Title,Description,Type,SavedMedia, Category) VALUES (:Title, :Description, :Type, :SavedMedia,:Category)");
			$stmt->bindParam(':Title',$title, PDO::PARAM_STR);
			$stmt->bindParam(':Description',$description, PDO::PARAM_STR);
			$stmt->bindParam(':Type',$type, PDO::PARAM_STR);
			$stmt->bindParam(':SavedMedia',$saved_media, PDO::PARAM_STR);
			$stmt->bindParam(':Category',$category, PDO::PARAM_STR);
			
			$title =  $data['Title'];
			$description = $data['Description'];
			$type = $data['Type'];
			$saved_media = $data['saved_media'];
			$category = $data['Category'];

			if($stmt->execute()){
				$data['MediaID'] = $this->conn->lastInsertId();
				$this->add_media_tags($data); // add tags for given media
				$media_attribs = array("ID"=>$data['MediaID'],"attributes"=>$data);
				$this->add_media_attributes($media_attribs); // add attributes of given media
				$result = array("MediaID"=>$data['MediaID'],"result"=>true);
				return $result;
				}else{ return false; }	
		}else{
			$result = array("result"=>"duplicate title");
			return $result;
		}
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
			$stmt = $this->conn->prepare("INSERT INTO `".$this->media_tags."` (MediaID,Tag) VALUES (:MediaID, :Tag)");
			$stmt->bindParam(':MediaID',$media_id, PDO::PARAM_INT);
			$stmt->bindParam(':Tag',$tag, PDO::PARAM_STR);
			
			$media_id =  $data['MediaID'];
			$tag =  $value;
			$stmt->execute();
		}
	}
	
	/**
	* Update Document/Asset
	* @param array $data ( field = field to be updated; field_value = new value of field )
	* @return integer $return
	*/
	public function update($data){
		extract($data); 
		$stmt = $this->conn->prepare("UPDATE `".$this->media."` SET `SavedMedia`=:SavedMedia, `SeoUrl`=:SeoUrl WHERE `MediaID`=:MediaID");
		$stmt->bindValue(':SavedMedia',$saved_media, PDO::PARAM_STR);
		$stmt->bindValue(':MediaID',$media_id, PDO::PARAM_INT);
		$stmt->bindValue(':SeoUrl',$seo_url, PDO::PARAM_STR);
		
		//$media_id =  $data['MediaID'];
		//$saved_media = $data['saved_media'];
		
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
	
	private function check_title( $title ){
		$stmt = $this->conn->prepare("SELECT COUNT(*) FROM `".$this->media."` WHERE Title=:Title");
		$stmt->bindParam(':Title',$title, PDO::PARAM_STR);
		$stmt->execute();
		$number_of_rows = $stmt->fetchColumn();
		return $number_of_rows;
		
	}
	
	
}