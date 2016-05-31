<?php
/**
 * Created by PhpStorm.
 * User: geanGin
 * Date: 5/19/16
 * Time: 2:31 PM
 */

class dbcon {
    private static $instance, $connection;

    private static $isCloud = true;
    private static $isDev = true;

    private function __construct(){

    }

    static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new dbcon();
        }

        return self::$instance;
    }

    static function getConnection(){
        if(!isset(self::$connection)){
            $host = self::$isDev?"localhost":"localhost";
            $port = 3306;
            $user = self::$isDev?"root":"root";
            $pass = self::$isCloud?"":"";
            $db_name = self::$isDev?"blamcodb":"blamcodb";

            try{
                $dsn = "mysql:dbname=".$db_name.";host=".$host.";port=".$port;
                self::$connection = new PDO($dsn, $user, $pass);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->exec("set names utf8");
            }catch (PDOException $e){
                echo "Connection failed ".$e->getMessage();
                self::$connection = null;
            }
        }
        return self::$connection;
    }
} 