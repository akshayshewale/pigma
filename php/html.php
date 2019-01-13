<?php
//this class provieds html functions such as validation etc

class HTML{
    function HTML(){
//        echo "<!DOCTYPE HTML>";
    }
    protected function error($text,$type){
            $error .= "<br><br>Please refer to the docs<br><br>";
            $error .= "$type :: ".$text."<br>";
            echo $error;
        }
    //help in validating the text if its in the type specified
    //validation(str $type,obj $text[,int $maxlenght[,bool required]])
    //available types are number,name,email,url,phone
    function validation(){
        $args = func_get_args();
        $cnt = count($args);
        $type = $args[0];
        $text = $args[1];
        if($cnt==4 and $args[3]==true){
            if(empty($text)){
                return false;
            }
            if(strlen($text)!=$args[3]){
                return false;
            }
        }
        if($cnt==3){
            if(strlen($text)!=$args[3]){
                return false;
            }
        }
        if($type=="number"){
            for($i=0;$i<strlen($text);$i++){
                if(!(is_int($text[$i]) || $text[$i]==".")){
                    return false;
                }
            }
        }
        elseif($type=="name"){
            $name = test_input($text);
            if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
                return false; 
            }
        }
        elseif($type=="email"){
            $email = test_input($text);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }
        elseif($type=="url"){
            $website = test_input($text);
            if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
                return false;
            }
        }
        elseif($type=="phone"){
            for($i=0;$i<strlen($text);$i++){
                if(!(is_int($text[$i]))){
                    return false;
                }
            }
        }
        else{
            $this->error("validation for $type is yet unavailable","Coming Soon");
        }
    }
}
?>
