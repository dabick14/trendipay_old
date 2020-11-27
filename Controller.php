<?php


include_once './logger/Logger.php';
Logger::configure('config.xml');
$logger = Logger::getLogger("Foo");

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

//include_once 'classes/StaticValues.php';


class Controller
{


    public function __construct (){

    }




    private $response;
    private $nextFunction;
    private $previousFunction;




    public function trueResponse(){
        $log = $this->initializeLogger();
        $this->response;

    }

    public function falseResponse(){

    }



    public function initializeLogger(){
        include_once './logger/Logger.php';
        Logger::configure('config.xml');
        return $logger = Logger::getLogger("Foo");
    }



}