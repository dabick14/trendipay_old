<?php

require_once './dbconfig/DB.php';
include 'StaticValues.php';
include_once './logger/Logger.php';
Logger::configure('config.xml');
$logger = Logger::getLogger("Foo");


//<!--read incoming data-- >

$request = file_get_contents("php://input");

//<!--convert the json object($request) to a PHP object.-->

$data = json_decode($request, true);
$logger->info($request);



session_id(md5($data['MSISDN']));
//print_r(session_id());
session_start();

//<!--Get All Incoming Request Parameters-- >

$ussd_id = $data['USERID'];
$msisdn = $data['MSISDN'];
$user_data = $data['USERDATA'];
$msgtype = $data['MSGTYPE'];
$id = session_id();

$sth = new DB();


$level = 0;
$static = new StaticValues();

//terminators
$back = "\n\n00.Back";
$choiceArray = array();



if (isset($_SESSION[$id]) and $msgtype == false) {
    $_SESSION[$id] = $_SESSION[$id] . $user_data;
    $user_dials = preg_split("/\#\*\#/",
        $_SESSION[$id]);


    print_r($user_dials);

    $choice = $user_data;

    $queryResult = $sth->queryLevel($id);
    $level = $queryResult['level'];
    //print_r($queryResult);


    $firstChoice = $user_dials[1];




    switch ($firstChoice) {
        case 1:
            $flow = $static::$AIRTIME;
            echo "1";
            break;
        case 2:
            $flow = $static::$BUNDLE;
            echo "2";
            break;
        case 3:
            $flow = $static::$BILL_PAYMENT;
            echo "3";
            break;
        case 4:
            $flow = $static::$MERCHANTS;
            echo "4";
            break;
        case 5:
            $flow = $static::$CONTACT;
            echo "5";
            break;
    }




//switch levels

    switch ($level){

        //level 1
            case 1:
            switch ($choice){
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:

                    $msg = $flow[$level+1];
                    echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                    //session id update
                    $_SESSION[$id] = $_SESSION[$id] . "#*#";

                if (isset($flow[$level+2])){
                    //promote level
                    $level = $static::$LEVEL_TWO;
                    $sth->updateLevel($id, $level);

                }elseif (!isset($flow[$level+2])) {
                    $level = $static::$LEVEL_CONFIRM_AMOUNT;
                    $sth->updateLevel($id, $level);
                }

                    //session_destroy();
                    break;

                case 99:
                    $msg = $flow[$level-1];
                    echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                    //session id update
                    $_SESSION[$id] = $_SESSION[$id] . "#*#";

                    //demote level
                    $level = $static::$LEVEL_TWO;
                    $sth->updateLevel($id, $level);
                    break;

                default:


            }
            break;

            case 2:
                switch ($choice) {

                    case 1:
                    case 2:
                    case 3:
                    case 4:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                    if (isset($flow[$level+2])){
                        //promote level
                        $level = $static::$LEVEL_THREE;
                        $sth->updateLevel($id, $level);

                    }elseif (!isset($flow[$level+2])) {
                        $level = $static::$LEVEL_CONFIRM_AMOUNT;
                        $sth->updateLevel($id, $level);
                    }

                        //session_destroy();
                        break;



                    case 99: //back
                        //$previousChoice = $_SESSION['choice'][0];

                        $msg = $flow[$level-1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        //demote level
                        $level = $static::$LEVEL_TWO;
                        $sth->updateLevel($id, $level);

                        //session_destroy();
                        break;



                }



                break;

            case 3:
                switch ($choice) {

                    case 1:
                    case 2:
                    case 3:
                    case 4:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_FOUR;
                            $sth->updateLevel($id, $level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            $sth->updateLevel($id, $level);
                        }



                        //session_destroy();
                        break;



                    case 99: //back
                        $msg = $flow[$level-1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        //demote level
                        $level = $static::$LEVEL_THREE;
                        $sth->updateLevel($id, $level);

                    //session_destroy();
                         break;




                }
            break;

            case 4:
            //$breakswitch = 2;
                echo "Here and now";
                break;

            case 5:
                echo "This is level 1.2 ";


        case $static::$LEVEL_CONFIRM_AMOUNT:
            $amount = $user_data;
            $msg = "Confirm you have to pay Gh".$amount;
            trueResponse($ussd_id, $msisdn, $user_data, $msg);

            $_SESSION[$amount] = $amount;

            //session id update
            $_SESSION[$id] = $_SESSION[$id] . "#*#";

            //promote level
            $level = $static::$LEVEL_PAYMENT_OPTIONS;
            $sth->updateLevel($id, $level);


            break;


        case $static::$LEVEL_PAYMENT_OPTIONS:
            $msg = $static::$PAYMENT_OPTIONS;

            case 00:

            break;








    }

   // echo "out of switch;

    }else{


    //<!--To reinitialize session variable in case the use cancelled initial screen-- >
    if (isset($_SESSION[$id]) and $msgtype == true) {
        session_unset();
    }

    /**
     * Stores user inputs using sessions.
     * You may also store user inputs in a database
     */

    $_SESSION[$id] = $user_data . "#*#";

// Responds to request. MSG variable will be displayed on the user's screen

    $msg = "Welcome To TrendiPay\n1. Buy Airtime\n2. Buy Data Bundles\n3. Bill Payment\n4. Merchants\n5. Contact Us";
    echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

    $level = $static::$LEVEL_ONE;
    $sth->insertLevel($id, $msisdn, $level);




}

//other functions

function trueResponse($user_id, $msisdn, $user_data, $msg){
    $resp = array("USERID" => $user_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
    $logger = Logger::getLogger("Foo");
    $logger->info($resp);
    return json_encode($resp);
}

function falseResponse($user_id, $msisdn, $user_data, $msg){
    $resp = array("USERID" => $user_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => false);
    $logger = Logger::getLogger("Foo");
    $logger->info($resp);
    return json_encode($resp);
}



