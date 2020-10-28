<?php



class DB {


    private static $instance = null;


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



}

