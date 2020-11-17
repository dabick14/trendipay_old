<?php

include_once './logger/Logger.php';
require_once "dbconfig.php";

Logger::getLogger('config.xml');



class DB {

    /**
     * @var null
     */
    private static $instance = null;
    public  $logger;
    public $value;

   public function __construct () {
       $this->logger = Logger::getLogger(__CLASS__);
       $this->value = new Values();
   }






    public function get()
    {
        //require_once "dbconfig.php";
        //$value = new Values();
        //$value->dbName;

        $this->value;

        $dsn = 'mysql:host='.$this->value->host.';dbname='.$this->value->dbName.';';

        if (self::$instance == null) {
            try {
                self::$instance = new PDO($dsn, $this->value->user, $this->value->password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //if (self::$instance){
                    //echo "working";
                //}
            } catch (PDOException $e) {
                // Handle this properly
                $this->logger->info($e->getMessage());
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

