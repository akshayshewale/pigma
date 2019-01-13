<?php
require_once("DeveloperUtils.php");
class Database{
public $hostname = "";
public $username = "";
public $password = "";
public $dbname = "";
public $con = "";
public $databaseNotConnected = false;
public $queryFailed = false;
public $tableDoesntExist = false;
public $tableExists = true;
//conectivity functions
//{
    //default constructor that connects to the database[needed]
    //Database([$_host,$_username,$_password,$_dbname])
    function Database(){
        $args = func_get_args();
        $this->hostname = $args[0];
        $this->username = $args[1];
        $this->password = $args[2];
        $this->dbname = $args[3];
        $this->connect();
    }
    protected function connect(){
        if(phpversion()<7){
            $this->con=mysql_connect($this->hostname,$this->username,$this->password) or die(mysql_error());
            $ret = mysql_select_db($this->dbname);
            if(!$ret){
                return $this->databaseNotConnected;
            }
            else{
                return $ret;
            }
        }
        elseif(phpversion()>=7){
            $this->con=mysqli_connect($this->hostname,$this->username,$this->password,$this->dbname) or die(mysqli_error($this->con));
            if(!$this->con){
                return $this->databaseNotConnected;
            }
            else{
                return $this->con;
            }
        }
    }
//}
//general functions
//{
    //creates json of the passed queries and headers
    //makeJson($queries,$header[,$databaseobject])
    function makeJson(){
        $args = func_get_args();
        if(count($args)==3){
            $allQueries = $args[0];
            $allHeaders = $args[1];
            if(count($allHeaders)==count($allQueries)){
                //creating json
                $all = '';
                for($i=0;$i<count($allHeaders);$i++){
                    $data = "";
                    $returned = $this->query($allQueries[$i]);
                    while($row = $this->fetch_assoc($returned))
                    {
                        $data.=json_encode($row).',';
                    }
                    $data = rtrim($data,',');
                    $data='"'.$allHeaders[$i].'":['.$data.'],';
                    $all.=$data;
                }
                $all = rtrim($all,",");
                return ('{"data":[{'.$all."}]}");
            }
        }
        else{
            return "please check your parameters headers not equal to queries";
        }
    }
    //allows you to fire a query to the connected db
    //query($query)
    function query($query){
        if(phpversion()<7){
            $ret = mysql_query($query,$this->con) or die("Error".mysql_error()."<br>query:".$query);
            if(!$ret){
                return $this->queryFailed;
            }
            else{
                return $ret;
            }
        }
        elseif(phpversion()>=7){
            $ret = mysqli_query($this->con,$query) or die("Error".mysqli_error($this->con)."<br>query:".$query);
            if(!$ret){
                return $this->queryFailed;
            }
            else{
                return $ret;
            }
        }
    }
    protected function error($text){
        $error = "<br><br>Please refer to the docs<br><br>";
        $error .= "SYNTAX ERROR::".$text."<br>";
        echo $error;
    }
//}
//mysql functions{
    //performs the data fetch from the given array
    //fetch_array($data)
    function fetch_array($data){
        $ret = "";
        if(phpversion()<7){
            $ret = mysql_fetch_array($data);
        }
        else if(phpversion()>=7){
            $ret = mysqli_fetch_array($data);
        }
        return $ret;
    }
    //performs the data fetch from the given associative array
    //fetch_assoc($data)
    function fetch_assoc($data){
        $ret = "";
        if(phpversion()<7){
            $ret = mysql_fetch_assoc($data);
        }
        else if(phpversion()>=7){
            $ret = mysqli_fetch_assoc($data);
        }
        return $ret;
    }
//}
//query functions
//{
    //insert functions
    //fires the insert query on the database
    //insert($table[,$values[,$returnQuery]])
    //insert($table[,$columns,$values[,$returnQuery]])
    //insert($table,$col1,$val1[,$col2,$val2,..[,$returnQuery]])
    function insert() {
        $args = func_get_args();
        $table = $args[0];
        $query = "";
        //insert into "tablename" values(val1,val2,val3...)
        if(count($args)==2 or (count($args)==3 and is_bool($args[2]) and $args[2]==true)){
            if(is_array($args[1])){
                $val = makeString($args[1]);    
            }
            else{
                $val = makeString(explode(",",$args[1]));
            }
            $query = "insert into $table values ($val);";
        }
        //insert into "tablename"(col1,col2,col3...) values(val1,val2,val3...)
        elseif(count($args)==3 or (count($args)==4 and is_bool($args[3]) and $args[3]==true)){
            if(is_string($args[1]) and is_string($args[2])){
                $col = makeString(explode(",",$args[1]),',','');
                $val = makeString(explode(",",$args[2]));
                $query = "insert into $table($col) values ($val);";
            }
            elseif(is_array($args[1]) and is_array($args[2])){
                $col = makeString($args[1],',','');
                $val = makeString($args[2],',');
                $query = "insert into $table($col) values ($val);";
            }
        }
        else{
            try{
                $col = '';
                $val = '';
                if(count($args)>1){
                    if(is_bool($args[count($args)-1]) and $args[count($args)-1]==true){
                        for ($j = 1; $j < count($args)-1; $j++){
                            if (($j % 2) == 0) {$val = $val . "'" . $args[$j] . "',";}
                            if (($j % 2) == 1) {$col = $col . "`" . $args[$j] . "`,";}
                        }
                    }
                    else{
                        for ($j = 1; $j < count($args); $j++){
                            if (($j % 2) == 0) {$val = $val . "'" . $args[$j] . "',";}
                            if (($j % 2) == 1) {$col = $col . "`" . $args[$j] . "`,";}
                        }
                    }
                    $val = rtrim($val, ",");
                    $col = rtrim($col, ",");
                    $query = "insert into $table($col) values($val)";
                }
                else{
                    $this->error("incorrect Parameters given for insert");
                }
            }
            catch(Exception $e){
                return e;
            }
        }
        if(is_bool($args[count($args)-1]) and $args[count($args)-1]==true){
            return $query;
        }
        else{
            return $this->query($query);
        }
    }
    //update functions
    //fires the update query on the database
    //update($table,$col=value)
    //update($table,str/array $col,str/array $value[,str/array $whereClause[,bool $returnQuery]])
    function update() {
        $args = func_get_args();
        $cnt = count($args);
        $table = $args[0];
        $query = "";
        if($cnt==2){
            $changes = $args[1];
            $query = "update $table set $changes";
        }
        else if($cnt==3 or ($cnt==4 and is_bool($args[3]) and $args[3]==true)){
            $changes = "";
            if(is_string($args[1]) and is_string($args[2])){
                $val = explode(',',$args[2]);
                $col = explode(',',$args[1]);
            }
            else if(is_array($args[1]) and is_array($args[2])){
                $val = $args[2];
                $col = $args[1];
            }
            else{
                return $this->error("Invalid Parameters");
            }
            for($i=0;$i<count($val);$i++){
                $changes .= "$col[$i]='$val[$i]',";
            }
            $changes = rtrim($changes,",");
            $query = "update $table set $changes";
        }
        else if($cnt==4 or ($cnt==5 and $args[4]==true)){
            $where = "";
            if(is_string($args[3])){
                $where = $args[3];
            }
            elseif(is_array($args[3])){
                $where = makeString($args[3],' and ','');
            }
            else{
                $this->error("Invalid Parameters");
            }
            $query = $this->update($table,$args[1],$args[2],true)." where $where";
        }
        else{
            return $this->error("invalid parameters");
        }
        if(is_bool($args[count($args)-1]) and $args[count($args)-1]==true){
            return $query;
        }
        else{
            return $this->query($query);
        }
    }
    //delete functions
    //fires the delete query on the database
    //delete($table[,str/array $where[,bool $returnQuery]])
    function delete(){
        $args = func_get_args();
        $table = $args[0];
        $cnt = count($args);
        if($cnt==1 or ($cnt==2 and is_bool($args[1]) and $args[1]==true)){
            $query = "delete from $table";
        }
        else if($cnt==2 or ($cnt==3 and is_bool($args[2]) and $args[2]==true)){
            $where = $args[1];
            if(is_array($args[1])){
                $where = makeString($args[1],' and ','');
            }
            elseif(is_string($args[1])){
                $where = $args[1];
            }
            $query = $this->delete($table,true)." where $where";
        }
        else{
            $this->error("Invalid parameters");
        }
        if(is_bool($args[count($args)-1]) and $args[count($args)-1]==true){
            return $query;
        }
        else{
            return $this->query($query);
        }
    }
    //select functions
    function select(){
        $args = func_get_args();
        $table = $args[0];
        $cnt = count($args);
        if($cnt==1 or ($cnt==2 and is_bool($args[1]) and $args[1]==true)){
            $query = "select * from $table";
        }
        else if($cnt==2 or ($cnt==3 and is_bool($args[2]) and $args[2]==true)){
            $cols = "";
            if(is_array($args[1])){
                $cols = makeString($args[1],',','');
            }
            elseif(is_string($args[1])){
                $cols = $args[1];
            }
            $query = "select $cols from $table";
        }
        else if($cnt==3 or ($cnt==4 and is_bool($args[3]) and $args[3]==true)){
            $where = "";
            if(is_array($args[2])){
                $where = makeString($args[2],' and ','');
            }
            elseif(is_string($args[2])){
                $where = $args[2];
            }
            $query = $this->select($table,$args[1],true)." where $where";
        }
        else if($cnt==4 or ($cnt==5 and is_bool($args[4]) and $args[4]==true)){
            $other = $args[3];
            $query = $this->select($table,$args[1],$args[2],true)." $other";
        }
        else{
            $this->error("Invalid parameters");
        }
        if(is_bool($args[count($args)-1]) and $args[count($args)-1]==true){
            return $query;
        }
        else{
            return $this->query($query);
        }
    }
//}
//    //table functions
//{
    //fires the drop query on the database to drop [default]table whose name is given or [optional]other objects whose name and type are given
    //drop(str/array $name[,str/array $type])
    function drop(){
        $args = func_get_args();
        $query = '';
        //default for table
        if(count($args)==1){
            $table = $args[0];
            if(is_array($args[0])){
                $table = makeString($args[0],',','');
            }
            $query .= "drop table $table;";
        }
        if(count($args)==2){
            $type = $args[0];
            $name = $args[1];
            if(is_array($type) and is_array($name)){
                $type = makeString($type,',','');
                $name = makeString($name,',','');
            }
            $query = "drop $type $name;";
        }
        return $this->query($query);
    }
    //check if Table Exists in database
    //drop(str $name)
    function checkTable($name){
        $flag = 0;
        $list = $this->query("show tables");
        while(($table = $this->fetch_array($list))!=null){
            if($table[0]==$name){
                return $this->tableExists;
            }
        }
        if($flag==0){
            return $this->tableDoesntExist;
        }
    }
}
?>
