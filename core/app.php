<?php

/*
 *   Date: 2019-06-01
 * Author: Antipov Oleg
 */

class App{

    function __construct () {

        define("URI", $_SERVER['REQUEST_URI']);
        define("ROOT", $_SERVER['DOCUMENT_ROOT']);
        
        date_default_timezone_set('Europe/Moscow');

        $directory = ROOT . '/app/';
        define("SUBPROJECT", array_values(array_diff(scandir($directory), array('..', '.', '.DS_Store'))));

    }

    function autoload () {
        // $this->require('/core/config/config.php'); 

        spl_autoload_register(function ($class) {

            $class = strtolower($class);
            
            if (file_exists(ROOT . '/core/classes/' . $class . '.php')) {

                require_once ROOT . '/core/classes/' . $class . '.php';

            } else if (file_exists(ROOT . '/core/helpers/' . $class . '.php')) {

                require_once ROOT . '/core/helpers/' . $class . '.php';

            } 

        });

        
        spl_autoload_register(function ($class) {
            foreach (SUBPROJECT as $k => $value) {
                if (file_exists(ROOT . '/app/'. $value .'/'. ucfirst($class) . '.php')) {
                    require_once ROOT . '/app/'. $value .'/'. ucfirst($class) . '.php';
                }
                if (file_exists(ROOT . '/app/'. $value .'/model/'. ucfirst($class) . '.php')) {
                    require_once ROOT . '/app/'. $value .'/model/'. ucfirst($class) . '.php';
                }
            }
        });


    
    }


    function start () {

        //session_name($this->config['sessionName']);
        //session_start();
        $controller = new Controller();
    }
    
}

?>