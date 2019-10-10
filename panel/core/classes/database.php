<?php

abstract class Database {

	public static $db;
	private $config = [];

    public function connect () {

    	require ROOT . "/core/config/config.php";

        try {

            self::$db = new PDO('mysql:host=' . $this->config['database']['hostname'] . ';dbname=' . $this->config['database']['dbname'],
                                $this->config['database']['username'], 
                                $this->config['database']['password']);

            self::$db->query('SET NAMES utf8');
            self::$db->query('SET CHARACTER_SET utf8_unicode_ci');
            
            // TODO: Remove for production
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            echo 'No connection DB: ' . $e->getMessage();

        }

    }

}

?>