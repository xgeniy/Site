<?php

class Model2 extends Database{

    //GET and POST params url
    public static $params_url = [];

    private static $query;

    private static $select = false;

    private static $table;

    //where
    private static $field = array();

    private static $filter = array();

    //sort
    private static $sort_field;

    private static $sort_type;

    //pagination
    private static $count_element;

    private static $number_page;

    //add
    private static $array_field_add = array();

    //edit
    private static $array_field_edit = array();

    private static $array_where = array();

    //delete
    private static $array_field_delete = array();

    //like
    private static $array_like = array();




    function __construct () {

        $this->connect();
        $this->setParams();
    }

    function viewJSON ($json, $type = null) {
        $result = ['result' => $json];

        if($type == null){
            
            header('Content-type:application/json;charset=utf-8');
            echo json_encode($result, JSON_UNESCAPED_UNICODE);

        }else if($type == 'mobile'){

            header('Content-Type: application/javascript');
            echo $_GET['callback'] . ' (' . json_encode($result, JSON_UNESCAPED_UNICODE) . ');';

        }

    }

    function view ($path, $data = []) {

        if (is_array($data))
            extract($data);

        require(ROOT . '/frontend/layouts/' . $path . '.php');

    }

    public static function table($table){

        self::$table = $table;

        return new self;
    }

    public static function get($field){

        self::$field = $field;
        self::$select = true;

        if( count(self::$field) > 0){
            self::$query = "SELECT ";
            //цикл
            foreach (self::$field as $k => $v) {
                if( $k + 1 == count(self::$field) ) {
                    self::$query .= $v;
                }else{
                    self::$query .= $v . ", ";
                }
            }
            self::$query .= " FROM " . self::$table;
        }else{
            self::$query = "SELECT * FROM " . self::$table;
        }

        return new self;
    }

    public function sort($field, $type){

        if(!empty($field) && !empty($type)){

            self::$sort_field = $field;
            self::$sort_type = $type;

            if($type == "desc"){

                self::$query .= " ORDER BY :sort_field DESC";

            }else if($type == 'ask'){

                self::$query .= " ORDER BY :sort_field ASK";

            }else{
                
                echo "ERROR! ->sort() type is not found $type";
                die();

            }
        }

        return new self;
    }

    public static function filter($array){

        if( count($array) > 0 ){
            self::$filter = $array;

            self::$query .= " WHERE ";

            $count = 0;
            foreach (self::$filter as $k => $v) {
                if( $count + 1 == count(self::$filter) ) {
                     self::$query .= $k . "= :filter_".$k." ";
                }else{
                     self::$query .= $k . "= :filter_".$k ." AND ";
                }
                $count += 1;
            }
        }
        else{

            echo "ERROR! ->filter() params count array " . count($filter);
            die();

        }

        return new self;
    }

    public static function search($array){

        if( count($array) > 0 ){

            self::$array_like = $array;
            //self::$query .= " LIKE ";

            $count = 0;
            foreach (self::$array_like as $k => $v) {

                if( $count + 1 == count(self::$array_like) ) {
                     self::$query .= $k . " LIKE ? ";
                }else{
                     self::$query .= $k . " LIKE ?  AND ";
                }
                $count += 1;
            }
        }
        else{

            echo "ERROR! ->search() params count array " . count($filter);
            die();

        }

        return new self;
    }

    public static function pagination($number_page, $count_element){
        
        if( isset($number_page) && isset($count_element) && is_int($number_page) && is_int($count_element) ){

            self::$number_page = $number_page;
            self::$count_element = $count_element;
            self::$query .= " LIMIT :number_page, :count_element ";

        }
        else{

            echo "ERROR! ->pagination( ". gettype($number_page) . "($number_page)" . " , " . gettype($count_element) . "($count_element)" . " ) invalid data format!";
            die();
        }

        return new self;
    }

    public static function edit($array_field, $array_where){

        if(count($array_field) > 0 && count($array_where) > 0){

            self::$array_where = $array_where;
            self::$array_field_edit = $array_field;
            self::$query = "UPDATE " . self::$table . " SET ";

            $count = 0;

            foreach (self::$array_field_edit as $k => $v) {

                if( $count + 1 == count(self::$array_field_edit) ) {
                     self::$query .= $k . " = :field_edit_" . $k . " ";
                }
                else{
                     self::$query .= $k . " = :field_edit_ ". $k . ", ";
                }

                $count += 1;
            }

            self::$query .= " WHERE ";

            $count = 0;

            foreach (self::$array_where as $k => $v) {

                if( $count + 1 == count(self::$array_where) ) {
                    self::$query .= $k . " = :field_where_". $k . " ";
                }
                else{
                    self::$query .= $k . " = :field_where_". $k . ", ";
                }

                $count += 1;
            }
        }

        return new self;
    }

    public static function add($array){

        if(count($array) > 0){

            self::$array_field_add = $array;
            self::$query = "INSERT INTO " . self::$table . " ( ";

            $count = 0;

            foreach (self::$array_field_add as $k => $v) {

                if( $count + 1 == count(self::$array_field_add) ) {
                     self::$query .= "`" . $k . "`";
                }
                else{
                     self::$query .= "`".$k."`, ";
                }

                $count += 1;
            }

            self::$query .= " ) VALUES (";

            $count = 0;

            foreach (self::$array_field_add  as $k => $v) {

                if( $count + 1 == count(self::$array_field_add) ) {
                     self::$query .= " :field_add_". $k ." ";
                }
                else{
                     self::$query .= " :field_add_". $k ." , ";
                }

                $count += 1;
            }

            self::$query .= " ) ";
        }

        return new self;
    }

    public static function delete($array){
        //DELETE FROM `db_g2`.`x16_users` WHERE `x16_users`.`id` = 4"
        if( count($array) > 0 ){

            self::$query = "DELETE FROM " . self::$table . " WHERE ";
            self::$array_field_delete = $array;
            $count = 0;

            foreach (self::$array_field_delete  as $k => $v) {

                if( $count + 1 == count(self::$array_field_delete) ) {
                     self::$query .= $k . "= :field_delete_". $k ." ";
                }
                else{
                     self::$query .= $k . "= :field_delete_". $k .", ";
                }

                $count += 1;
            }


        }
        return new self;
    }

    public static function send(){
        
        $sth = self::$db->prepare(self::$query);


        //select  filter
        if( count(self::$filter) > 0 ){

            foreach (self::$filter as $k => $v) {
                $sth->bindParam(":filter_".$k, $v);
            }
        }        

        //search
        if( count(self::$array_like) > 0 ){

            foreach (self::$array_like as $k => $v) {

                $count += 1;
                $sth->bindParam($count, '%'.$v.'%', PDO::PARAM_STR);
                //$sth->bindValue(':term', '%'.$query.'%');

            }

        }

        //edit
        if( count(self::$array_field_edit) > 0  && count(self::$array_where) > 0 ){

            foreach (self::$array_field_edit as $k => $v) {
                $sth->bindParam(":field_edit_" . $k, $v);
            }

            foreach (self::$array_where as $k => $val) {
                $sth->bindParam(":field_where_" . $k, $val);
            }

        }

        //add
        if( count(self::$array_field_add) > 0 ){

            foreach (self::$array_field_add as $k => $v) {
                $sth->bindParam(":field_add_".$k, $v);
            }

        }

        //delete
        if( count(self::$array_field_delete) > 0 ){

            foreach (self::$array_field_delete as $k => $v) {
                $sth->bindParam(":field_delete_" .$k, $v);
            }

        }

        //sort
        if( !empty(self::$sort_field) ){

            $sth->bindParam(":sort_field", self::$sort_field);
            //$sth->bindParam($count, self::$sort_field);

        }

        //pagination
        if( isset(self::$number_page) && isset(self::$count_element) ){

            $sth->bindParam(":number_page", self::$number_page, PDO::PARAM_INT);
            $sth->bindParam(":count_element", self::$count_element, PDO::PARAM_INT);
            
        }

        try{

            var_dump(self::$query);

            $sth->execute();

            //select
            if(self::$select == true)
                return $sth->fetchAll(\PDO::FETCH_ASSOC);
            //add or edit
            if( count(self::$array_field_add) || count(self::$array_field_edit))
                return self::$db->lastInsertId();


        }
        catch( PDOException $e ){

            echo ("Error execute query " . $e->getMessage());
            //$sth->debugDumpParams();

        }
    
    }

    private function setParams(){

        $allowed_char = " \t\n\r\0\x0B'";

        if(count($_POST)){

            foreach ($_POST as $k => $v) {
                if(!empty($v)){
                    self::$params_url[$k] = trim(filter_input(INPUT_POST, $k), $allowed_char);
                }
            }

        }

        if(count($_GET)){

            foreach ($_GET as $k => $v) {

                if(!empty($v)){
                    self::$params_url[$k] = trim(filter_input(INPUT_GET, $k), $allowed_char);
                }
                
            }

        }

    }



}

?>