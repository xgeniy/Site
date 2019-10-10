<?php

/*
 * Every class derriving from Model has access to $this->db
 * $this->db is a PDO object
*/

class Microservice {

    private $params_url = array();

    function __construct () {
        $this->setParams();
    }

    function getMicroservice () {
        try{

            $this->viewJSON(SUBPROJECTS);

    	}catch (Exception $e){

            echo ("Error query " . $e->getMessage());

    	}
    }

    function changeMicroservice () {
    
        try{

            if( !empty($this->params_url['name']) && !empty($this->params_url['include']) ){
                
                if($this->params_url['include'] == 'true'){

                    $src = ROOT . "/panel/archive/microservice/".$this->params_url['name']."/";
                    $dest = ROOT . "/app/";

                    shell_exec("cp -r $src $dest");
                    shell_exec("sudo rm -R $src ");
                }

                if($this->params_url['include'] == 'false'){

                    $src = ROOT . "/app/".$this->params_url['name']."/";
                    $dest = ROOT . "/panel/archive/microservice/";

                    shell_exec("cp -R $src $dest");
                    shell_exec("rm -R $src");
                }
            }

        }catch (Exception $e){

            echo ("Error query " . $e->getMessage());

        }
    }

    function viewJSON ($json, $type = null) {
        $result = ['result' => $json];

        if($type == null){
            
            header('Content-type:application/json;charset=utf-8');
            echo json_encode($result, JSON_UNESCAPED_UNICODE);

        }else if($type == 'mobile'){

            header('Content-Type: application/javascript');
            echo $_GET['callback'] . ' (' . json_encode($result, JSON_UNESCAPED_UNICODE) . ');';

        }

    }

    private function setParams(){

        $allowed_char = " \t\n\r\0\x0B'";

        if(count($_POST)){

            foreach ($_POST as $k => $v) {
                if(!empty($v)){
                    $this->params_url[$k] = trim(filter_input(INPUT_POST, $k), $allowed_char);
                }
            }

        }

        if(count($_GET)){

            foreach ($_GET as $k => $v) {

                if(!empty($v)){
                    $this->params_url[$k] = trim(filter_input(INPUT_GET, $k), $allowed_char);
                }
                
            }

        }

    }

}

?>