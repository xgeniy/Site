<?php

class Requests_types extends Model
{
	public function addReqType(){
        $art = Model::table("requests_types")->add(array("name" => self::$params_url["name"], "description" => self::$params_url["description"]))->send();
    	$rows = $art->fetchAll(PDO::FETCH_ASSOC); 
		$this->viewJSON($rows);
    }

    public function getReqType(){
        $grt = self::$db->prepare("SELECT * FROM 'requests_types' WHERE id= :id");
		$result = $grt->execute(array(":id" => self::$params_url['id']));
		//Model::table('requests')->get (array("type","status"))->send();
    	$rows = $grt->fetchAll(PDO::FETCH_ASSOC); 
		$this->viewJSON($rows);
    }
	
	public function editReqType(){
        $ert = Model::table("requests_types")->edit(array("name" => self::$params_url["name"], "description" => self::$params_url['description']))->send();
    	$rows = $ert->fetchAll(PDO::FETCH_ASSOC); 
		$this->viewJSON($rows);
	}
	
	public function delReqType(){
		$drt = self::$db->prepare("DELETE FROM 'requests_types' WHERE id= :id");
		$result = $drt->execute(array(":id" => self::$params_url['id']));
    	$rows = $drt->fetchAll(PDO::FETCH_ASSOC); 
		$this->viewJSON($rows);
	}
    
    public function getReqTypeAll(){
		$grat = Model::table('requests_types')->get (array("requests_types"))->send();
    	$rows = $grat->fetchAll(PDO::FETCH_ASSOC); 
		$this->viewJSON($rows);
	}
    
    public function sortReqType(){
        $srt = self::$db->prepare('SELECT * FROM `` ORDER BY ``.`` ASC');
        $result = $srt->execute(array(":id" => self::$params_url['id']));
        Model::table('requests')->get (array($_POST)[""])->send();
    	$rows = $srt->fetchAll(PDO::FETCH_ASSOC); 
		$this->viewJSON($rows);
	}
}
?>