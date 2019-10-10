<?php

/*
 * Every class derriving from Model has access to $this->db
 * $this->db is a PDO object
 * Has a config in /core/config/database.php
 */

class PanelController extends Controller{

	//список всех микросервисов
  	function getMicroservice(... $args){ echo (new Microservice())->getMicroservice(); }

  	//включение * выключение микросервиса
    function changeMicroservice(... $args){ echo (new Microservice())->changeMicroservice(); }



}

?>