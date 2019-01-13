<?php
require_once("DeveloperUtils.php");//checked and working
//contains all general utilities like date,time,etc
class Utils{
    //default constructor
    //Utils([,$timezone])
    function Utils(){
        $args = func_get_args();
        if(count($args)==0){
            date_default_timezone_set("Asia/Kolkata");
        }
        elseif(count($args)==1){
            date_default_timezone_set($args[0]);
        }
        else{
            $this->error("Invalid Initialization parameters");
        }
    }
    protected function error($text){
        $error = "<br><br>Please refer to the docs<br><br>";
        $error .= "SYNTAX ERROR::".$text."<br>";
        echo $error;
    }
    //set the time zone to the provided one
    //setTimezone($timezone)
    function setTimezone($text){
        date_default_timezone_set($text);
    }
    //gets the date at the number of days after [default]current date or [optional]provided date with [optional]given format{default:'Y-m-d'}
    //getDateAt($num[,$date[,$format]]){}
    function getDateAt(){
        $args = func_get_args();
        $format = "Y-m-d";
        $dateString = '';
        //returns the date n days from current date(may be negative or positive) in the default format
        //function getDateAt($num){}
        if(count($args)==1){
            $dateString = strtotime((date($format)).$args[0]." days");
        }
        //returns the date n days from provided date(may be negative or positive) in the default format
        //function getDateAt($num,$date){}
        elseif(count($args)==2){
            $dateString = strtotime($args[1].$args[0]." days");
        }
        //returns the date n days from provided date(may be negative or positive) in the provided format
        //function getDateAt($num,$date,$format){}
        elseif(count($args)==3){
            $dateString = strtotime($args[1].$args[0]." days");
            $format = $args[2];
        }
        else{
            return $this->error('invalid number of parameters passed');
        }
        return date($format, $dateString);
    }
    //gets the time at the number of hours after [default]current time or [optional]provided time with [optional]given format{default:'H:i:s'}
    //getTimeAt($num[,$time[,$format]]){}
    function getTimeAt(){
        $args = func_get_args();
        $format = "H:i:s";
        $dateString = '';
        //returns the date n days from current date(may be negative or positive) in the default format
        //function getDateAt($num){}
        if(count($args)==1){
            $dateString = strtotime((date($format))." +0 days ".$args[0]." hours");
        }
        //returns the date n days from provided date(may be negative or positive) in the default format
        //function getDateAt($num,$date){}
        elseif(count($args)==2){
            $dateString = strtotime($args[1]." +0 days ".$args[0]." hours");
        }
        //returns the date n days from provided date(may be negative or positive) in the provided format
        //function getDateAt($num,$date,$format){}
        elseif(count($args)==3){
            $dateString = strtotime($args[1]." +0 days ".$args[0]." hours");
            $format = $args[2];
        }
        else{
            return $this->error('invalid number of parameters passed');
        }
        return date($format, $dateString);
    }
    //gets the full datetime after the number of days and hours after [default]current datetime or [optional]provided datetime with [optional]given format{default:'Y-m-d H:i:s'}
    //getTimeAt($num[,$datetime[,$format]]){}
    function getDateTimeAt(){
        $args = func_get_args();
        $format = "Y-m-d H:i:s";
        $dateString = '';
        //returns the date n days from current date(may be negative or positive) in the default format
        //function getDateAt($num){}
        if(count($args)==2){
            $dateString = strtotime((date($format))." $args[0] days ".$args[1]." hours");
        }
        //returns the date n days from provided date(may be negative or positive) in the default format
        //function getDateAt($num,$date){}
        elseif(count($args)==3){
            $dateString = strtotime($args[2]." $args[0] days ".$args[1]." hours");
        }
        //returns the date n days from provided date(may be negative or positive) in the provided format
        //function getDateAt($num,$date,$format){}
        elseif(count($args)==4){
            $dateString = strtotime($args[2]." $args[0] days ".$args[1]." hours");
            $format = $args[3];
        }
        else{
            return $this->error('invalid number of parameters passed');
        }
        return date($format, $dateString);
    }
    //gets the [default]current date or [optional]provided date with [optional]given format{default:'Y-m-d'}
    //function getDate($format,$date){}
    function getDate(){
        $args = func_get_args();
        //returns the current date in the default format
        //function getDate(){}
        $format = 'Y-m-d';
        $date = date($format);
        //returns the current date in the specified format
        //function getDate($format){}
        if(count($args)==0){
            
        }
        elseif(count($args)==1){
            $format = $args[0];
        }
        //returns the specified date in the specified format
        //function getDate($format,$date){}
        elseif(count($args)==2){
            $format = $args[0];
            $date = $args[1];
        }
        else{
            return $this->error('invalid number of parameters');
        }
        $returndate = new DateTime($date);
        return $returndate->format($format);
    }
    //gets the [default]current time with [optional]given format{default:'H:i:s a'}
    //getTime([$format]]){}
    function getTime(){
        $args = func_get_args();
        if(count($args)==0){
            $format = "H:i:s a";
            $time = date($format);
            return $time;
        }
        elseif(count($args)==1){
            return $time = date($args[0]);
        }
        else{
            return $this->error('invalid number of parameters parameters');
        }
    }
    //gets the [default]current datetime or [optional]provided datetime with [optional]given format{default:'Y-m-d H:i:s'}
    //getFullDate([$format[,$date]]){}
    function getFullDate(){
        $args = func_get_args();
        if(count($args)==0){
            return date("Y-m-d H:i:s");
        }
        elseif(count($args)==1){
            return date($args[0]);
        }
        elseif(count($args)==2){
            $date =  new DateTime($args[1]);
            return $date->format($args[0]);
        }
        else{
            return $this->error('invalid number of parameters parameters');
        }
    }
}
?>
