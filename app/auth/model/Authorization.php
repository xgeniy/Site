<?php
 
class Authorization extends Model{

    public function example(){

        $this->viewJSON( Model::table("users_person_data")->get()->sort('id', 'desc')->send() );
        // $stmt = self::$db->prepare("SELECT * FROM  `users_person_data` ORDER BY :field DESC");

        // $id = "id";
        // $stmt->bindValue(":field", $id, PDO::PARAM_STR);

        // $result_query = $stmt->execute();

        // $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        // var_dump($rows);

        // $this->viewJSON($rows);
    }
    
    /*********************************** AUTHORIZATION BLOCK ************************************/
    public function startAuth() {

        try{
            if( !empty(self::$params_url['login']) &&  !empty(self::$params_url['password']) ){
                //авторизация по логин и паролю
                $this->startAuthToLogin();

            }else if( !empty(self::$params_url['phone']) &&  !empty(self::$params_url['password']) ){
                //авторизация по номеру телефона и паролю
                $this->startAuthToPhone();
            }else{
                $this->viewJSON("Error not found params");
            }

        }catch(Exception $e){
            echo ("Error query " . $e->getMessage());
        }
    }

    public function startAuthToLogin () {

        try{
                
            $auth_data = Model::table("users_person_data")->get(array('id'))->filter(array("login" => self::$params_url['login'], "password" => $this->getHashPassword(self::$params_url['password'])))->send();

            if( !empty($auth_data[0]['id']) ){
                
                $security_key = $this->generateSecurityKey($auth_data[0]['id']);  

                Model::table("users_security_key")->add(array("security_key" => $security_key, "id_user" => $auth_data[0]['id'], "time_create" => date('Y-m-d H:i:s') ))->send();

                $this->viewJSON(array("security_key" => $security_key));

            }else{
                $this->viewJSON(array("error" => "Не правильный логин и пароль"));
            }

    	}catch (Exception $e){

            echo ("Error query " . $e->getMessage());

    	}
    }

    public function startAuthToPhone () {

        try{
                
            $auth_data = Model::table("users_person_data")->get(array('id'))->filter(array("phone" => self::$params_url['login'], "password" => $this->getHashPassword(self::$params_url['password'])))->send();

            if( !empty($auth_data[0]['id']) ){
                
                $security_key = $this->generateSecurityKey($auth_data[0]['id']);  

                Model::table("users_security_key")->add(array("security_key" => $security_key, "id_user" => $auth_data[0]['id'], "time_create" => date('Y-m-d H:i:s') ))->send();

                $this->viewJSON(array("security_key" => $security_key));

            }else{
                $this->viewJSON(array("error" => "Не правильный логин и пароль"));
            }

        }catch (Exception $e){

            echo ("Error query " . $e->getMessage());

        }
    }

    /*********************************** REGISTRATION BLOCK ************************************/

    public function startRegistration() {

        try{

            if( !empty(self::$params_url['email']) && filter_var(self::$params_url['email'], FILTER_VALIDATE_EMAIL) ){
                //регистрация по email
                $this->startRegistrationToEmail();

            }else if( !empty(self::$params_url['phone']) ){
                //регистрация по номеру телефона
                //$this->startAuthToPhone();
            }else{
                $this->viewJSON("Error not found params to registration");
            }
        }
        catch(Exception $e){
            echo ("Error query " . $e->getMessage());
        }
    }

    private function startRegistrationToEmail(){
        try{
            if($this->existEmail()){
                $user_card_id = Model::table("users_cards")->add(array('status' => 'unverified', 'role' => 'user'))->send();
                $user_person_data_id = Model::table("users_person_data")->add(array('id' => $user_card_id, 'login' => 'example'))->send();
            }
            else{
                $this->viewJSON("Ошибка! Данный email уже зарегитрирован!"); 
            }
        }
        catch(Exception $e){
            echo ("Error query " . $e->getMessage());
        }
    }

    //проверка на существование  email в базе
    private function existEmail(){
        try{
            $email = Model::table("users_person_data")->get(array("email"))->filter(array("email" => self::$params_url['email']))->send();

            if(!empty($email[0]['email'])) return false;
            else return true;
        }
        catch(Exception $e){
            echo ("Error query " . $e->getMessage());
        }
    }


    /*********************************** REGISTRATION BLOCK END ************************************/


    //выход из системы
    public function startExit(){
        try{
            if(!empty($_COOKIE['security_key'])){
                Model::table("users_security_key")->delete(array("security_key" => $_COOKIE['security_key']))->send();
            }
        }
        catch(Exception $e){
            echo ("Error query " . $e->getMessage());
        }
    }

    /*
    **
    ** PRIVATE FUNCTION
    ** 
    */
    
    private function getHashPassword($password){
        return hash("sha512", self::$config['salt'] . $password);
    }

    private function generateSecurityKey($id){
        return hash("sha512", time() . $id . self::$config['salt']);
    }


}

?>