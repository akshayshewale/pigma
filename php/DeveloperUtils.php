<?php
//error
function error($text){
    echo $text;
}
//simpler isset($_REQUEST['name']);
//checkTrue($name)
function checkTrue($name){
    return isset($_REQUEST["$name"]);
}
//simpler post method protocol
//post($name[,$index])
function post(){
    $n = func_num_args();
    $a = func_get_args();
    
    if($n==1){
        //post(str tagname)
        return $_POST[$a[0]];
    }
    elseif($n==2){
        //post(str tagname,int index)
        return $_POST[$a[0]][$a[1]];
    }
    else{
        return error("Invalid Parameters");
    }
}
//simpler get method protocol
//get($name[,$index])
function get(){
    $n = func_num_args();
    $a = func_get_args();
    
    if($n==1){
        //get(str tagname)
        return $_GET[$a[0]];
    }
    elseif($n==2){
        //get(str tagname,int index)
        return $_GET[$a[0]][$a[1]];
    }
    else{
        return error("Invalid Parameters");
    }
}
//simpler request method protocol
//request($name[,$index])
function request(){
    $n = func_num_args();
    $a = func_get_args();
    
    if($n==1){
        //request(str tagname)
        return $_REQUEST[$a[0]];
    }
    elseif($n==2){
        //request(str tagname,int index)
        return $_REQUEST[$a[0]][$a[1]];
    }
    else{
        return error("Invalid Parameters");
    }
}
//simpler files method protocol
//files($name[,$index[,$index]])
function files(){
    $n = func_num_args();
    $a = func_get_args();
    //files(str tagname)
    if($n==1){
        return $_FILES[$a[0]];
    }
    //files(str tagname,int/str index)
    elseif($n==2){
        return $_FILES[$a[0]][$a[1]];
    }
    //files(str tagname,int/str index,int/str index)
    elseif($n==3){
        return $_FILES[$a[0]][$a[1]][$a[2]];
    }
    else{
        return error("Invalid Parameters");
    }
}
//simpler session method protocol
//session($name[,$value])
function session(){
    $n = func_num_args();
    $a = func_get_args();
    //session(str tagname)
    if($n==1){
        //session(str tagname)
        return $_SESSION[$a[0]];
    }
    //session(str tagname,obj value)
    elseif($n==2){
        //session(str tagname,obj value)
        return $_SESSION[$a[0]]=$a[1];
    }
    else{
        return error("Invalid Parameters");
    }
}
//simpler server method protocol
//server($name)
function server($name){
    //server(str index)
    return $_SERVER[$name];
}
//simpler env method protocol
//env($name)
function env($name){
    //env(str index)
    return $_ENV[$name];
}
//simpler cookie method protocol
//server($name[,value[,expire[, path[, domain[, secure[, httponly]]]]]])
function cookie(){
    $n = func_num_args();
    $a = func_get_args();
    if($n==1){
        //cookie(str tagname)
        return $_COOKIE[$a[0]];
    }
    elseif($n==2){
        //cookie(str tagname,str value)
        return setcookie($a[0],$a[1]);
    }
    elseif($n==3){
        //cookie(str tagname,str value,int time)
        return setcookie($a[0],$a[1],$a[2]);
    }
    elseif($n==4){
        //cookie(str tagname,str value,int time,str path)
        return setcookie($a[0],$a[1],$a[2],$a[3]);
    }
    elseif($n==5){
        //cookie(str tagname,str value,int time,str path,str domain)
        return setcookie($a[0],$a[1],$a[2],$a[3],$a[4]);
    }
    elseif($n==6){
        //cookie(str tagname,str value,int time,str path,str domain,bool secure)
        return setcookie($a[0],$a[1],$a[2],$a[3],$a[4],$a[5]);
    }
    elseif($n==7){
        //cookie(str tagname,str value,int time,str path,str domain,bool secure,bool httponly)
        return setcookie($a[0],$a[1],$a[2],$a[3],$a[4],$a[5],$a[7]);
    }
}
//gets the ip of the local machine(also client machine)
//getIp()
function getIp(){
    if(!empty(server('HTTP_CLIENT_IP'))){
        return server('HTTP_CLIENT_IP');
    }
    elseif(!empty(server('HTTP_CLIENT_IP'))){
        return server('HTTP_X_FORWARDED_FOR');
    }
    else{
        return server('REMOTE_ADDR');
    }
}
//generates an otp of numberof num digits
//generateOTP($length)
function generateOTP($num){
    $start = 10**($num-1);
    $end = (10**$num)-1;
    return rand($start,$end);
}
//creates a javascript alert of the value of the mentioned text
//alert($text)
function alert($element){
    echo "<script>alert('".$element."');</script>";
}
//creates a javascript with mentioned code
//javascript($code);
function javascript($element){
    echo "<script type='text/javascript'>$element</script>";
}

//function replacePattern($mainString,$toReplace,$valuesToInsert);
function replacePattern(){
    $args = func_get_args();
    $mainString = $args[0];
    $toReplace = $args[1];
    $valuesToInsert = $args[2];
    $explodedArray = explode($toReplace,$mainString);
    $returnString = "".$explodedArray[0];
    for($i=0;$i<count($valuesToInsert);$i++){
        $returnString .= $valuesToInsert[i].$explodedArray[i+1];
    }
    return $returnString;
}
//makes string of an array
//makeString($val[,$seperator[,$container]])
function makeString(){
    $n = func_num_args();
    $args = func_get_args();
    $val = "";
    $sep = ",";
    $container = "'";
    if($n==2){
        $sep=$args[1];
    }
    elseif($n==3){
        $sep=$args[1];
        $container=$args[2];
    }
    foreach($args[0] as $v){
        $val.="$container$v$container$sep";
    }
    $val = rtrim($val,$sep);
    return $val;
}
//redirects browser as per given string
//redirect($path)
function redirect($path,$time){
    if($time==null){
        javascript("window.location.href='$path';");
    }else{
        javascript("setTimeout(function(){window.location.href='$path';},$time)");
    }
}
?>
