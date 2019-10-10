<?php

class Security extends Model{

    public $security_id;

    public function isSecurity(){
        if(isset($_COOKIE['security_token']) && !empty($_COOKIE['security_token'])) {


            $stmt = self::$db->prepare("SELECT `x16_tokens`.`id_user` FROM  `x16_tokens` WHERE `x16_tokens`.`token`= :token");
            $result = $stmt->execute(array(":token" => $_COOKIE['security_token']));

            if(!empty($stmt->fetchColumn())){
                $this->security_id = $stmt->fetchColumn();
                return true;
            }else{
                return false;
            }

        }else{
            return true;
        }

        // if(!empty($_GET['security_login'])) {
        //     $phone = $this->restPhone(filter_input(INPUT_GET, 'security_login'));
        //     $m->data['user']['login'] = $phone;
        // }
        // if(!empty($_GET['security_password'])) {
        //     $m->data['user']['password'] =  filter_input(INPUT_GET, 'security_password');
        // }
        // if(!empty($_GET['security_token'])) {
        //     $m->data['token']['token'] = filter_input(INPUT_GET, 'security_token');
        // }
        // if(!empty($_POST['security_token'])) {
        //     $m->data['token']['token'] = filter_input(INPUT_POST, 'security_token');
        // }
        // else if (isset($_COOKIE['security_token']) && $_COOKIE['security_token']!='') {
        //     $m->data['token']['token'] = $_COOKIE['security_token'];
        // }
        // // $m->data['token']['time']['start'] = time();
        // // $m->data['token']['time']['finish'] = $m->data['token']['time']['start'] + 10*24*3600;
        // $this->timestamp = date('Y-m-d H:i:s');
        
    
    }

}

?>