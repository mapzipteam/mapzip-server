<?php
/*
Usage example #1

$imgdir_manager = new MapzipImageDir();
$dir_name = "../client_data/client_".$value['userid'];
$imgdir_manager->setClientDirPath($dir_name);
$to_client['state_imgdir'] = $imgdir_manager->executeClientDir();



*/
class MapzipImageDir{
	private $client_dir_path;
	private $store_dir_path;
	private $response;
	function MapzipImageDir(){
		$this->response = array();
	}
	function setClientDirPath($string){
		$this->client_dir_path = $string;
		$this->response['set_client_path'] = $this->client_dir_path;
		return $this->response;
	}
	function setStoreDirPath($store_id){
		$this->store_dir_path = $this->client_dir_path."/store_{$store_id}";
		$this->response['set_store_path'] = $this->store_dir_path;
		return $this->response;
	}
	function executeClientDir(){
		$dir_name = $this->client_dir_path;
		if(is_dir($dir_name)){
			return CLIENT_REVIEW_IMAGE_MKDIR_EXIST;
		}
		else{
			if(@mkdir($dir_name,0777)){
				return CLIENT_REVIEW_IMAGE_MKDIR_SUCCESS;
			}
			else{
				return CLIENT_REVIEW_IMAGE_MKDIR_FAIL;
			}
		}
		
	}
	function executeStoreDir(){
		$dir_name = $this->store_dir_path;
		if(is_dir($dir_name)){
			return CLIENT_REVIEW_IMAGE_MKDIR_EXIST;
		}
		else{
			if(@mkdir($dir_name,0777)){
				return CLIENT_REVIEW_IMAGE_MKDIR_SUCCESS;
			}
			else{
				return CLIENT_REVIEW_IMAGE_MKDIR_FAIL;
			}
		}
	}

}

?>