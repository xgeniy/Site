<?php

/*
 * Every class derriving from Model has access to $this->db
 * $this->db is a PDO object
*/

 
class UsersCards extends Model2 {

    public static $table_name = "users_cards";

    public $id;

    public $firstname;

    public $lastname;

    public $patronymic;

    public $status;

    public $role;

    public $avatar;

    public $parent_id;

    public $nickname;





}

?>