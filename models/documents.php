<?php

class Documents extends Database{

	function __construct(){
		$dbconn = parent::__construct();
		foreach($dbconn->query('SELECT * from pd_products_attributes') as $row) {
			print_r($row);
		}
		//print __CLASS__;
	}
	
	/**
	* Add New Document/Asset
	* @param array $data
	*/
	public function add(){
		
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
}