<?php

/*
 *   Date: 2019-08-26
 *   Author: Antipov Oleg
 */

class Panel{

    function __construct () {

        define("URI", $_SERVER['REQUEST_URI']);
        define("ROOT", $_SERVER['DOCUMENT_ROOT']);

        $directory = ROOT . '/app/';
        define("SUBPROJECTS", array_values(array_diff(scandir($directory), array('..', '.', '.DS_Store'))));

    }

    function autoload () {

        // $this->require('/core/config/config.php'); 

        spl_autoload_register(function ($class) {

            $class = strtolower($class);

            if (file_exists(ROOT . '/panel/core/classes/' . $class . '.php')) {
        
                require_once ROOT . '/panel/core/classes/' . $class . '.php';

            } else if (file_exists(ROOT . '/panel/core/helpers/' . $class . '.php')) {

                require_once ROOT . '/panel/core/helpers/' . $class . '.php';

            } 

        });

        spl_autoload_register(function ($class) {
            $class = strtolower($class);
           
            if (file_exists(ROOT . '/panel/model/'. ucfirst($class) . '.php')) {
                require_once ROOT . '/panel/model/'. ucfirst($class) . '.php';
            }
                
        });
    }


    function start () {

        //session_name($this->config['sessionName']);
        //session_start();
        $route = explode('/', URI);

        if(count($route) == 1){
            if(!empty($route[1])){
                $route[1] = strtolower($route[1]);
            }
        }
    
        if ( !empty($route[1]) && file_exists(ROOT . '/panel/controller/' . ucfirst($route[1]).'Controller' . '.php') ) {

            $this->require('/panel/controller/' . ucfirst($route[1]).'Controller' . '.php');
            $baseController = ucfirst(ucfirst($route[1]).'Controller');
            $controller = new $baseController();

        } else if ( !empty($route[1]) && !file_exists(ROOT . '/panel/controller/' . ucfirst($route[1]).'Controller' . '.php') ) {

            echo "ERROR! " . ucfirst($route[1]).'Controller' . '.php' . " is not found."; 

        }else {
            echo "Hello! Welcome to G2 framework!";
        }
        
    }

    function require ($path) {
        require ROOT . $path;

    }
    
}

?>