<?php

//require_once './dbconfig/DB.php';
include 'classes/StaticValues.php';
//include_once './logger/Logger.php';
require __DIR__ . '/vendor/autoload.php';
Logger::configure('config.xml');
$logger = Logger::getLogger("Foo");


//<!--read incoming data-- >

$request = file_get_contents("php://input");

//<!--convert the json object($request) to a PHP object.-->

$data = json_decode($request, true);
$logger->info($request);




//print_r(session_id());
session_start();

session_id(md5($data['MSISDN']));

//<!--Get All Incoming Request Parameters-- >

$ussd_id = $data['USERID'];
$msisdn = $data['MSISDN'];
$user_data = $data['USERDATA'];
$msgtype = $data['MSGTYPE'];
$id = session_id();

//$sth = new DB();


//$level = 0;
$static = new StaticValues();

//terminators
$back = "\n\n00.Back";
$choiceArray = array();



if (isset($_SESSION[$id]) and $msgtype == false) {
    $_SESSION[$id] = $_SESSION[$id] . $user_data;
    $user_dials = preg_split("/\#\*\#/",
        $_SESSION[$id]);


    //print_r($user_dials);

    $choice = $user_data;

    //$queryResult = $sth->queryLevel($id);
    $level = $_SESSION['level'];
    //$level = $queryResult['level'];
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
                    //airtime
                    case 1:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_TWO;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
                        }

                        $_SESSION['option1'] = "airtime";

                        //session_destroy();
                        break;
                    

                    //data 
                    case 2:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_TWO;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
                        }

                        $_SESSION['option1'] = "bundle";

                        
                        break;


                    //bill payments     
                    case 3:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_TWO;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
                        }
                        $_SESSION['option1'] = "paybill";

                        //session_destroy();
                        break;
                    case 4:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_TWO;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
                        }

                        $_SESSION['option1'] = "merchant";

                        //session_destroy();
                        break;
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

        //level 2   
            case 2:
                switch ($choice) {

                    case 1:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_THREE;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
                        }
                        $_SESSION['option2'] = "MTN";

                        //session_destroy();
                        break;

                    case 2:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_THREE;
                            updateLevel($level);;

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);;
                        }

                        //session_destroy();
                        break;

                    case 3:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_THREE;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
                        }

                        //session_destroy();
                        break;

                    case 4:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                    if (isset($flow[$level+2])){
                        //promote level
                        $level = $static::$LEVEL_THREE;
                        updateLevel($level);

                    }elseif (!isset($flow[$level+2])) {
                        $level = $static::$LEVEL_CONFIRM_AMOUNT;
                        updateLevel($level);
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
                        updateLevel($level);

                        //session_destroy();
                        break;



                }



                break;

        //level 3
            case 3:
                switch ($firstChoice) {

                    //airtime
                    case 1:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";
                        $_SESSION['recepient'] = $choice;

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_FOUR;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
                        }



                        //session_destroy();
                        break;
                    case 2:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_FOUR;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
                        }



                        //session_destroy();
                        break;
                    case 3:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_FOUR;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
                        }



                        //session_destroy();
                        break;
                    case 4:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_FOUR;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
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
                        updateLevel($level);

                    //session_destroy();
                         break;




                }
                break;

            case 4:
                switch ($firstChoice){

                    //airtime
                    case 1:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";
                        $_SESSION['amount'] = $choice;

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_FIVE;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
                        }

                        $_SESSION['option1'] = "airtime";

                        //session_destroy();
                        break;

                    case 2:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_FIVE;
                            updateLevel($level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);
                        }

                        $_SESSION['option1'] = "bundle";

                        //session_destroy();
                        break;
                    case 3:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_FIVE;
                            $sth->updateLevel($id, $level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            $sth->updateLevel($id, $level);
                        }
                        $_SESSION['option1'] = "paybill";

                        //session_destroy();
                        break;
                    case 4:
                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                        if (isset($flow[$level+2])){
                            //promote level
                            $level = $static::$LEVEL_FIVE;
                            $sth->updateLevel($id, $level);

                        }elseif (!isset($flow[$level+2])) {
                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            $sth->updateLevel($id, $level);
                        }

                        $_SESSION['option1'] = "merchants";

                        //session_destroy();
                        break;
                    case 5:

                        $msg = $flow[$level+1];
                        echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                        //session id update
                        $_SESSION[$id] = $_SESSION[$id] . "#*#";

                    if (isset($flow[$level+2])){
                        //promote level
                        $level = $static::$LEVEL_FIVE;
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
                    echo "Default";


                }
                break;
            case 5:
                echo "This is level 1.2 ";


        case $static::$LEVEL_CONFIRM_AMOUNT:

            //print_r($user_dials);
            $amount = end($user_dials);
            $_SESSION['dueAmount'] = $amount;
            
            switch($firstChoice){

                //airtime
                case 1:
                    $recepientNumber = $_SESSION['recepient'];
                    $msg = "Confirm you have to pay Gh".$amount."for ".$recepientNumber."\n\n1.Yes\n2.No";
                    echo trueResponse($ussd_id, $msisdn, $user_data, $msg);
                break;

                //billpayment
                case 3:
                    $msg = "Confirm amount to pay is Gh".$amount;
                    echo trueResponse($ussd_id, $msisdn, $user_data, $msg);
                break;


            }
            //end($user_dials);
            
            //$msg = "Confirm you have to pay Gh".$amount;
            //echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

            

            //session id update
            $_SESSION[$id] = $_SESSION[$id] . "#*#";

            //promote level
            $level = $static::$LEVEL_PAYMENT_OPTIONS;
            updateLevel($level);


            break;



        case $static::$LEVEL_PAYMENT_OPTIONS:
            
            switch($choice){
                case 1:
                    $msg = $static::$PAYMENT_OPTIONS[1];
                    echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                    $level = $static::$LEVEL_SUCCESSFUL_PAYMENT;
                    updateLevel($level);

                    //session id update
                    $_SESSION[$id] = $_SESSION[$id] . "#*#";
                break;
                case 2:
                    //$optionOne = $_SESSION['option1'];
                    switch ($firstChoice){
                        case 1:
                            $msg = $static::$PAYMENT_OPTIONS[$firstChoice+1];
                        break;
                        case 2:
                            $msg = $static::$PAYMENT_OPTIONS[$firstChoice+1];
                        break;
                        case 3:
                            $msg = $static::$PAYMENT_OPTIONS[$firstChoice+1];
                        break;
                        case 4:
                            $msg = $static::$PAYMENT_OPTIONS[$firstChoice+1];
                        break;
                        case 5:
                            $msg = $static::$PAYMENT_OPTIONS[$firstChoice+1];
                        break;

                    }
                    //$msg = $static::$PAYMENT_OPTIONS[2];
                    echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                    $level = $static::$LEVEL_EDIT;
                    updateLevel($level);
                    //session id update
                    $_SESSION[$id] = $_SESSION[$id] . "#*#";
                break;
                default:

                    $msg = $static::$INVALID_INPUT;
                    echo falseResponse($ussd_id, $msisdn, $user_data, $msg);

                    $level = $static::$LEVEL_SUCCESSFUL_PAYMENT;
                    updateLevel($level);

                    //session id update
                    $_SESSION[$id] = $_SESSION[$id] . "#*#";
            break;
            }
        break;



        case $static::$LEVEL_EDIT:

            $optionOne = $_SESSION['option1'];

            switch($optionOne){
                //airtime
                case 'airtime':

                    switch($user_data){

                        //edit network
                        case 1:
                            $msg = $static::$EDIT_OPTIONS[1][1];
                            echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                            $level = $static::$LEVEL_TWO;
                            updateLevel($level);

                            //session id update
                            $_SESSION[$id] = $_SESSION[$id] . "#*#";
                        break;

                        //edit number
                        case 2:
                            $msg = $static::$EDIT_OPTIONS[1][2];
                            echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                            $level = $static::$LEVEL_THREE;
                            updateLevel($level);

                            //session id update
                            $_SESSION[$id] = $_SESSION[$id] . "#*#";
                        break;

                        //edit amount
                        case 3:
                            $msg = $static::$EDIT_OPTIONS[1][3];
                            echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                            $level = $static::$LEVEL_CONFIRM_AMOUNT;
                            updateLevel($level);

                            //session id update
                            $_SESSION[$id] = $_SESSION[$id] . "#*#";
                        break;

              
                    }
                break;

                case 2:
                break;

                //billpayments
                case 'paybill':
                    switch($user_data){

                        //edit bill choice
                        case 1:
                            $msg = $static::$EDIT_OPTIONS[3][1];
                            echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                            $level = $static::$LEVEL_TWO;
                            updateLevel($level);

                            //session id update
                            $_SESSION[$id] = $_SESSION[$id] . "#*#";
                        break;

                        //edit account number
                        case 2:
                            $msg = $static::$EDIT_OPTIONS[3][2];
                            echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

                            $level = $static::$LEVEL_THREE;
                            updateLevel($level);

                            //session id update
                            $_SESSION[$id] = $_SESSION[$id] . "#*#";
                        break;

                        default:


                    break;

                        
                        

              
                    }
                break;


        }

        break;



        case $static::$LEVEL_SUCCESSFUL_PAYMENT:

            //TODO call charge API
            $amount = $_SESSION['dueAmount'];
            $MSISDN = $msisdn;
            $user_data = "";

            $msg = "Thank you. Please approve your payment";

            echo falseResponse($ussd_id, $msisdn, $user_data, $msg);

            session_destroy();





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

    $msg = "Welcome To TrendiPay\nThe Way to Pay!\n1. Buy Airtime\n2. Buy Data Bundles\n3. Bill Payment\n4. Merchants\n5. Contact Us";
    echo trueResponse($ussd_id, $msisdn, $user_data, $msg);

    $level = $static::$LEVEL_ONE;
    updateLevel($level);




}



//other functions

function trueResponse($user_id, $msisdn, $user_data, $msg){
    $resp = array("USERID" => $user_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
    $logger = Logger::getLogger("Foo");
    $logger->info(json_encode($resp));
    return json_encode($resp);
}

function falseResponse($user_id, $msisdn, $user_data, $msg){
    $resp = array("USERID" => $user_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => false);
    $logger = Logger::getLogger("Foo");
    $logger->info(json_encode($resp));
    return json_encode($resp);
}

function processOptions(){
    $optionOne = $_SESSION['option1'];
    $optionTwo = $_SESSION['option2'];
    if ($optionOne == "airtime" ) {
        if ($optionTwo == "MTN") {
            $serviceID = 1;
            return $serviceID;
        } elseif($optionTwo == "AIRTEL") {
            $serviceID = 2;
            return $serviceID;
        } elseif ($optionTwo == "Vodafone"){
            $serviceID = 3;
            return $serviceID;
        } elseif ($optionTwo == "GLO"){
            $serviceID = 4;
            return $serviceID;
        } 
        
    } elseif ($optionOne == "data") {
        if ($optionTwo == "MTN") {
            $serviceID = 5;
            return $serviceID;
        } elseif($optionTwo == "AIRTEL") {
            $serviceID = 6;
            return $serviceID;
        } elseif ($optionTwo == "Vodafone"){
            $serviceID = 7;
            return $serviceID;
        } elseif ($optionTwo == "GLO"){
            $serviceID = 8;
            return $serviceID;
        } 

    } elseif ($optionOne = "paybill") {
        if ($optionTwo == "SunPower") {
            $serviceID = 9;
            return $serviceID;
        } elseif($optionTwo == "ECG") {
            $serviceID = 10;
            return $serviceID;
        } elseif ($optionTwo == "multichoice"){
            $serviceID = 11;
            return $serviceID;
        } elseif ($optionTwo == "Water"){
            $serviceID = 12;
            return $serviceID;
        } 

    }

}

function updateLevel($level){
    $_SESSION['level'] = $level;
}


