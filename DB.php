<?php

class DB{
    private static $connection;
    public static function GetConnection(){
        try{
            if(empty(self::$connection)) {
                $conn = new \PDO("mysql:host=191.243.143.162;dbname=", '', '');
                $conn->exec("set names utf8");
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$connection = $conn;
                return self::$connection;
            }else{
                return self::$connection;
            }
        } catch(\PDOException $e){
            throw new \Exception($e->getMessage());
        }
    }

    public static function select($query, $parameters = null){
        try{
            $items = [];
            if(empty(self::$connection)){
                $sql = DB::GetConnection();
            }else{
                $sql = self::$connection;
            }

            $exec = $sql->prepare($query);
            if (is_array($parameters)) {
                foreach ($parameters as $key => &$value) {
                    $exec->BindParam($key, $value);
                }
            }

            $exec->execute();
            while($data = $exec->fetch(\PDO::FETCH_ASSOC)){
                $items[] = $data;
            }

            foreach ($items as $key => $item) {
                $items[$key]=array_change_key_case($item, CASE_LOWER);
            }

            return $items;
        }catch(\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    public static function selectWithQuestion($query, $parameters = null){
        try{
            $items = [];
            if(empty(self::$connection)){
                $sql = DB::GetConnection();
            }else{
                $sql = self::$connection;
            }

            $exec = $sql->prepare($query);
            if (is_array($parameters)) {
                $i=1;
                foreach ($parameters as $key => &$value) {
                    $exec->BindParam($i, $value);
                    $i++;
                }
            }

            $exec->execute();
            while($data = $exec->fetch(\PDO::FETCH_ASSOC)){
                $items[] = $data;
            }

            foreach ($items as $key => $item) {
                $items[$key]=array_change_key_case($item, CASE_LOWER);
            }

            return $items;
        }catch(\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    public static function insert($query, $parameters = null) {
        try {
            if(empty(self::$connection)){
                $sql = DB::GetConnection();
            }else{
                $sql = self::$connection;
            }
            $exec = $sql->prepare($query);
            if (is_array($parameters)) {
                foreach ($parameters as $key => &$value) {
                    $exec->BindParam($key, $value);
                }
            }

            $exec->execute();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public static function insertWithQuestion($query, $parameters = null) {
        try {
            if(empty(self::$connection)){
                $sql = DB::GetConnection();
            }else{
                $sql = self::$connection;
            }
            $exec = $sql->prepare($query);
            if (is_array($parameters)) {
                $i=1;
                foreach ($parameters as $key => &$value) {
                    $exec->BindParam($i, $value);
                    $i++;
                }
            }

            $exec->execute();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public static function update($query, $parameters = null) {
        try {
            if(empty(self::$connection)){
                $sql = DB::GetConnection();
            }else{
                $sql = self::$connection;
            }

            $exec = $sql->prepare($query);
            if (is_array($parameters)) {
                foreach ($parameters as $key => &$value) {
                    $exec->BindParam($key, $value);
                }
            }

            $exec->execute();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public static function updateWithQuestion($query, $parameters = null) {
        try {
            if(empty(self::$connection)){
                $sql = DB::GetConnection();
            }else{
                $sql = self::$connection;
            }
            $exec = $sql->prepare($query);
            if (is_array($parameters)) {
                $i=1;
                foreach ($parameters as $key => &$value) {
                    $exec->BindParam($i, $value);
                    $i++;
                }
            }

            $exec->execute();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public static function setTransaction(){
        try{
            if(empty(self::$connection)){
                $sql = DB::GetConnection();
            }else{
                $sql = self::$connection;
            }

            $sql->beginTransaction();
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    public static function commit(){
        try{
            if(empty(self::$connection)){
                $sql = DB::GetConnection();
            }else{
                $sql = self::$connection;
            }

            $sql->commit();
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    public static function rollback(){
        try{
            if(empty(self::$connection)){
                $sql = DB::GetConnection();
            }else{
                $sql = self::$connection;
            }

            if($sql->inTransaction()){
                $sql->rollback();
            }
        }catch (\Exception $ex){
            dd($ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    public static function nextIncrement($tableName){
        try {
            $sqlVerifica = "SELECT AUTO_INCREMENT as id
                        FROM  INFORMATION_SCHEMA.TABLES
                        WHERE TABLE_NAME   = :table";
            $params=array(':table' => $tableName);

            return DB::select($sqlVerifica, $params);

        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }
    }
}
