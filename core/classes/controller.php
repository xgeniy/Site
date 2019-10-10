<?php

class Controller{

    private $route = [];

    function __construct (){ $this->initialization(); }

    private function initialization(){

        $this->route = explode('/', URI);

        if(!empty($this->route[3])){
            $arr = explode("?", $this->route[3]);
            $this->route[3] = $arr[0];
        }

        if(count($this->route) == 1){
            if(!empty($this->route[1])){
                $this->route[1] = strtolower($this->route[1]);
            }
        }

        if( !empty($this->route[1]) ){

            if($this->route[1] == "api"){

                if ( file_exists(ROOT . '/app/' . $this->route[2] . '/Public' . ucfirst($this->route[2]).'Controller' . '.php') && file_exists(ROOT . '/app/' . $this->route[2] . '/Private' . ucfirst($this->route[2]).'Controller' . '.php') ) {
                    
                    
                    $this->require('/app/' . $this->route[2] . '/Public' . ucfirst($this->route[2]).'Controller' . '.php');
                    $this->require('/app/' . $this->route[2] . '/Private' . ucfirst($this->route[2]).'Controller' . '.php');
                
                    $baseController = "Public" . ucfirst(ucfirst($this->route[2]).'Controller');
                    $publicController = new $baseController();

                    $baseController = "Private" . ucfirst(ucfirst($this->route[2]).'Controller');
                    $privateController = new $baseController();

                    if( method_exists($publicController, $this->route[3]) ){
                        call_user_func_array(array($publicController, $this->route[3]), array());
                    }
                    else if( method_exists($privateController, $this->route[3]) ){

                        $security = new Security();

                        if( $security->isSecurity() ){
                            call_user_func_array(array($privateController, $this->route[3]), array());
                        }else{
                            echo "ERROR ACCESS! invalid security_key!";
                        }
                    }
                    else{
                        echo 'ERROR! public function ' . $this->route[3] . '() not found in Public' . ucfirst($this->route[2]).'Controller' . '.php and Private' . ucfirst($this->route[2]).'Controller' . '.php';
                    }
                }else{
                    echo 'ERROR! microservice ' . $this->route[2] . ' not found!';
                }

            }
            else{
                if ( file_exists(ROOT . '/frontend/pages/' . $this->route[1].  '.html') ) {

                    $this->require('/frontend/pages/' . $this->route[1].  '.html');  

                }else{
                    echo "ERROR! Page not found!";
                }
            }
        }
        else{
             echo "Hello! Welcome to G2 framework!";
        }
    }

    private function require ($path) {
        require ROOT . $path;

    }

}

?>