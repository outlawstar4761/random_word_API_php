<?php
require_once __DIR__ . '/credentials.php';

class Db{
    public $query;
    public $select;
    public $table;
    public $where;
    public $and;
    public $or;
    public $like;
    public $link;
    public $between;
    public $data = array();
    public $database;

    public function __constructor(){
        $this->query = "";
    }

    public function database($database){
        $this->database = $database;
        $this->query = '';
        return $this;
    }
    public function connect(){
        $this->link = mysqli_connect(HOST,USER,PASSWORD,$this->database);
        if (!$this->link) {
            $exceptionStr = "Connection Failed: " . mysqli_connect_error();
            throw new Exception($exceptionStr);
        }
        return true;
        //return $this;
    }
    public function select($select){
        $this->select = "SELECT " . $select . " FROM ";
        $this->query .= "SELECT " . $select . " FROM " . $this->table . "\n";
        return $this;
    }
    public function table($table){
        $this->table = "$table";
        return $this;
    }
    public function where($where,$conditional,$condition){
        $this->query .= " WHERE " . $where . " " . $conditional . " " . "" . $condition . "";
        //$this->where = " WHERE " . $where . " " . $conditional . " " . "" . $condition . "" . ";";
        //$this->rwhere = $where;
        return $this;
    }
    public function andWhere($where,$conditional,$condition){
        $this->query .= " AND " . $where . " " . $conditional . " " . $condition . "";
        return $this;

    }
    public function orWhere($where,$conditional,$condition){
        $this->query .= " OR " . $where . " " . $conditional . " " . $condition . "";
        return $this;
    }
    public function orderBy($condition){
        $this->query .= " ORDER BY " . $condition . "\n";
        return $this;
    }
    public function insert($data){
        $str = "INSERT INTO " . $this->table . " (";
        foreach($data as $key=>$value){
            $str .= $key . ",";
        }
        $str .= ")";
        $str = preg_replace('/,([^,]*)$/', '\1', $str);
        //echo $str . "<br>";
        $str .= " VALUES (";
        foreach($data as $key=>$value){
            $str .= "'" . $value . "',";
        }
        $str .= ")";
        $str = preg_replace('/,([^,]*)$/', '\1', $str);
        $this->query = $str;
        return $this;
    }
    public function update($data){
        $colCount = count($data);
        $i = 0;
        $str = "UPDATE " . $this->table . " SET ";
        foreach($data as $key=>$value){
            if(++$i == $colCount){
                $str .= $key . " = '" . $value . "'";
            }else{
                $str .= $key . " = '" . $value . "' ,";
            }
        }
        $this->query = $str;
        return $this;
    }

    /*THE EXAMPLE BELOW DEMONSTRATES PROPER USAGE OF PUT*/
    //$db->database("test")
    //->table("test")
    //->insert($data,$data)
    //->put();
    public function put(){
        $this->connect();
        $sql = $this->query;
        if (!mysqli_query($this->link,$sql)){
            $exceptionStr = "Query Failed: " . mysqli_error($this->link);
            throw new Exception($exceptionStr);
        }
        if(!mysqli_close($this->link)){
            throw new Exception(mysqli_error($this->link));
        }
        return true;
    }


    /*THE EXAMPLE BELOW DEMONSTRATES THE PROPER USE OF GET*/
    /*$results = $db->database("test")
            ->table("test")
            ->select("*")
            ->where("username", "=", "'crabLegz'")
            ->get();
            */

    public function get($structure = "object"){
        if(!$this->connect()){
            echo "can't connect<br>";
        }
        $sql = $this->query;
        $results = mysqli_query($this->link,$sql);
        if (!$results){
            throw new Exception(mysqli_error($this->link));
        }
        if (!mysqli_close($this->link)){
            throw new Exception(mysqli_error($this->link));
        }
        else{
            switch ($structure){
                case "object":
                    return $results;
                    break;
                default:
                    return $results;
            }
        }
        //return $this;
    }
}
/*$cols = array("Title","Director","Genre");
$values = array("Clerks","Kevin Smith","Comedy");
$db = new Db();
$db->table('tv');
$db->insert($cols,$values);*/