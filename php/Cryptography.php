<?php
require_once("DeveloperUtils.php");//checked and working
class Cryptography{
public $alpha = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',' ','','-',',','.',';',"'",'"','/','[',']','=','-','`','~','!','@','#','$','%','^','&','*','(',')','_','+','{','}',"\\","|",':','<','>','?','0',"1","2","3","4",'5',"6","7","8","9");
public $cnt = 71;
protected function error(){

}
//lets you set your character set for using in encoding and decoding
function setArray($array){
    $this->alpha = $array;
    $this->cnt = count($array);
}
//classic encryption using ceasar cipher
function encrypt($text,$key){
    $Etxt = "";
    $text = strtolower($text);
    for($p=0;$p<strlen($text);$p++){
        $pos = $key+(array_search($text[$p],$this->alpha));
        $Etxt .= $this->alpha[($pos)%$this->cnt];
    }
    return $Etxt;
}
//classic decryption using ceasar cipher
function decrypt($text,$key){
    $Dtxt = "";
    $text = strtolower($text);
    for($p=0;$p<strlen($text);$p++){
        $t = $text[$p];
        $ans = array_search($t,$this->alpha);
        $pos = $ans-$key;
        if($pos<0){
            $pos = $this->cnt-abs($pos);
        }
        $Dtxt .= $this->alpha[(($pos)%$this->cnt)];
    }
    return $Dtxt;
}
//encryption using ceasar cipher using random key returns the return array($key,$cipherText);
//this can be decrypted using the decrypt method
function randomCipher($text){
    $key = rand(2,27);
    $cipherText = $this->encrypt($text,$key);
    return array($key,$cipherText);
}
//encryption using the querty array
function quertyEncrypt($text,$key){
    $querty = array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m","1","2","3","4","5","6","7","8","9","0","`","~","!","@","#","$","%","^","&","*","(",")","_","+","-","=","[","]","\\",";","'",",",".","/","{","}","|",":",'"',"<",">","?"," ");
    $cr = new Cryptography();
    $cr->setArray($querty);
    $Etxt = "";
    $text = strtolower($text);
    $Etxt = $cr->encrypt($text,$key);
    return $Etxt;
}
//decryption using the querty array
function quertyDecrypt($text,$key){
    $querty = array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m","1","2","3","4","5","6","7","8","9","0","`","~","!","@","#","$","%","^","&","*","(",")","_","+","-","=","[","]","\\",";","'",",",".","/","{","}","|",":",'"',"<",">","?"," ");
    $cr = new Cryptography();
    $cr->setArray($querty);
    $Etxt = "";
    $text = strtolower($text);
    $Etxt = $cr->decrypt($text,$key);
    return $Etxt;
}
//encryption using transposition cipher
function encryptTransposition($text,$key){
    $array = array();
    $text = makeString(explode(" ",$text),'','');
    $enc = "";
    if(strlen($text)%$key!=0){
        for($i=0;$i<=(strlen($text)%$key);$i++){
            $text .= $this->alpha[rand(0,($this->cnt-1))];
        }
    }
    for($i=0;$i<=intval(strlen($text)/$key);$i++){
        $array[$i]=array();
    }
    for($i=0;$i<strlen($text);$i++){
        $array[$i%($key+1)][count($array[$i%($key+1)])] = $text[$i];
    }
    foreach($array as $word){
        $enc .= makeString($word,"","");
    }
    return $enc;
}
}
?>
