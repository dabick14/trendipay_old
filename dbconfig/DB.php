<?php

include './logger/Logger.php';
Logger::configure('config.xml');


class DB {

    private Logger $logger;

   public function __construct () {
       $this->logger = Logger::getLogger(__CLASS__);
   }


    private static  $instance = null;


    public static function get()
    {
        require_once "dbconfig.php";
        $value = new Values();
        //$value->dbName;


        $dsn = 'mysql:host='.$value->host.';dbname='.$value->dbName.';';

        if (self::$instance == null) {
            try {
                self::$instance = new PDO($dsn, $value->user, $value->password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //if (self::$instance){
                    //echo "working";
                //}
            } catch (PDOException $e) {
                // Handle this properly
                echo "Error: " . $e->getMessage();
            }
        }
        return self::$instance;
    }


    public function insert ($sql) {
        $sth = self::get();
        $sth->exec($sql);
    }


    //SELECT level from session_levels WHERE sessionID =  ORDER BY `timestamp` LIMIT 1

    public function queryLevel ($session_id) {
        $sth = self::get();
        $sql = "SELECT level from session_levels WHERE sessionID =:session_id ORDER BY timestamp DESC LIMIT 1 ";
        $stmt = $sth->prepare($sql);
        $stmt->execute(['session_id' => $session_id]);
        return $stmt->fetch();
    }

    public function insertLevel ($id, $msisdn, $level) {
        $sql = "INSERT into session_levels (sessionID, phoneNumber, level) VALUES ('$id', '$msisdn', '$level')";
        self::insert($sql);
    }

    public function updateLevel($id, $level){
        $sth = self::get();
        $sql = "UPDATE session_levels SET level =:level WHERE sessionID=:session_id";
        $stmt= $sth->prepare($sql);
        $stmt->execute(['session_id'=> $id, 'level'=>$level]);
    }

    public function demoteLevel($id, $level){
        $sth = self::get();
        $sql = "UPDATE session_levels SET level =:level WHERE sessionID=:session_id";
        $stmt= $sth->prepare($sql);
        $stmt->execute(['session_id'=> $id, 'level'=>$level]);
    }


}

