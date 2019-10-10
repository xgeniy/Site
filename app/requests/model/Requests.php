<?php

class Requests extends Model
{

	public function addReq(){
		$ar = self::$db->prepare("INSERT INTO requests(timestamp_begin) VALUES  (:timestamp_begin)");
    	
    	$result = $ar->execute(array(":timestamp_begin" => self::$params_url['timestamp_begin']));
    	
    	$rows = $ar->fetchAll(PDO::FETCH_ASSOC); 
		
		$this->viewJSON($rows);
    }

    public function getReq(){
       
        $gr = self::$db->prepare("SELECT `requests`.timestamp_begin,`requests`.timestamp_end,`requests_responses`.status,`requests_types`.name,`requests_types`.description,`requests_types`.description FROM requests,requests_responses,requests_types WHERE `requests`.id= :id");
		
		$result = $gr->execute(array(":id" => self::$params_url['id']));
   		
   		$rows = $gr->fetchAll(PDO::FETCH_ASSOC); 
		
		$this->viewJSON($rows);
    }
    
	public function editReq(){
        
        $er = Model::table("requests")->edit(array("timestamp_end" => self::$params_url["timestamp_end"]))->send();
    	
    	$rows = $er->fetchAll(PDO::FETCH_ASSOC); 
		
		$this->viewJSON($rows);
	}
	
	public function delReq(){
		
		$dr = self::$db->prepare("DELETE FROM requests,requests_responses,requests_types WHERE `requests`.`id`= :id AND `requests_responses.id`= :id_response AND `requests_types`.`id`= :id_types");
		
		$result = $dr->execute(array(":id" => self::$params_url['id'], ":id_response" =>self::$params_url[':id_response'],"id_types" => self::$params_url['id_types']));
    	
    	$rows = $dr->fetchAll(PDO::FETCH_ASSOC); 
		
		$this->viewJSON($rows);
	}
    
    public function getReqAll(){
          
        if ( empty( $_GET ['fieldsort'] ) || empty ( $_GET [ 'type' ] ) ) {

            printf("\n Enter right field and sort type\n");
            
        }

        else {
            
            $fieldsort = $_GET['fieldsort'];

            $type = $_GET['type'];
            
        }

        $query = "SELECT * FROM requests";  
        
        $array = array();

        if (!empty($fieldsort) && !empty($type)){

            if ($type == "1"){
                
                $type = "ASC";
            
            }

            else if ($type == "2"){

                $type = "DESC";

            }  
           
            else if  ($type != "1" || $type != "2" ){

                printf("Enter right type for sort\n");

                return 0;

            }

            $sort = " ORDER BY $fieldsort $type";

            $query.=$sort;

        }

            $gra = self::$db->prepare($query);
        
            $result = $gra->execute($array);
        
            $rows = $gra->fetchAll(PDO::FETCH_ASSOC); 
        
            $this->viewJSON($rows);

    }
	
    public function sortReq(){
        
        $sr = self::$db->prepare("SELECT * FROM requests ORDER BY :field ASC");
        
        $result = $sr->execute(array(":field" => self::$params_url['field']));
    	
    	$rows = $sr->fetchAll(PDO::FETCH_ASSOC); 
		
		$this->viewJSON($rows);
	}
    
	public function searchReq() {
        
        $src = self::$db->prepare("SELECT * FROM requests WHERE :field = :search");
        
        $result = $src->execute(array(":field" => self::$params_url['field'], ":search" => self::$params_url['search']));
 
        $rows = $src->fetchAll(PDO::FETCH_ASSOC); 
        
        $this->viewJSON($rows);
    }

    public function getReqResp(){
        
        $grr = self::$db->prepare("SELECT * FROM requests_types,requests LEFT JOIN requests_responses ON (`requests_responses`.id=`requests`.id_response) WHERE `requests`.`id`= :id ORDER BY`requests`.id ASC");
		
		$result = $grr->execute(array(":id" => self::$params_url['id']));
   		
   		$rows = $grr->fetchAll(PDO::FETCH_ASSOC); 
		
		$this->viewJSON($rows);
    }
}

?>
