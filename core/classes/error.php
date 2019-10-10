<?php

class Error {

    function __construct (){
        var_dump(123);
    }


    public function error($exception){

       var_dump($exception);
    
    }

}

?>