<?php

/*
 * Every class derriving from Model has access to $this->db
 * $this->db is a PDO object
 * Has a config in /core/config/database.php
 * branch b1
 */

class PrivateAuthController {

    public function example(){ echo (new Authorization())->example(); }

    //регистрация пользователя
    
    //проверка токена или ключа
    
    //выход из системы
    public function exit(){ echo (new Authorization())->startExit(); }
    
    //восстановление пароля
    
    //восстановление логина



}

?>