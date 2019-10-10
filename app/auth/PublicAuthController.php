<?php

/*
 * Every class derriving from Model has access to $this->db
 * $this->db is a PDO object
 * Has a config in /core/config/database.php
 */

class PublicAuthController {

	//авторизация пользователя
    public function authorization(){ echo (new Authorization())->startAuth(); }

    //регистрация пользователя
    public function registration(){ echo (new Authorization())->startRegistration(); }
    
    //проверка токена или ключа
    
    //выход из системы
    
    //восстановление пароля
    
    //восстановление логина



}

?>