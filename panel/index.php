<?php 
	
/*
 *   Date: 2019-08-26
 * Author: Oleg Antipov
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', E_ALL);


require __DIR__ . '/core/panel.php';

$panel = new Panel();

$panel->autoload();

$panel->start();


 ?>