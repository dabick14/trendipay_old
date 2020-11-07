<?php

require_once 'DB.php';
include 'StaticValues.php';
include('Logger.php');
$logger = Logger::getLogger("main");
$logger->info("This is an informational message.");
$logger->warn("I'm not feeling so good...");

//<!--read incoming data-- >

$request = file_get_contents("php://input");

//<!--convert the json object($request) to a PHP object.-->

$data = json_decode($request, true);



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
$static = new StaticValues();
$level = 0;

//$sql = "INSERT into session_levels (sessionID, phoneNumber, level) VALUES ('$id', '$msisdn', '$level')";
//$sth->insertLevel($id, $msisdn, $level);


//terminators
$back = "\n\n00.Back";




if (isset($_SESSION[$id]) and $msgtype == false) {
    $_SESSION[$id] = $_SESSION[$id] . $user_data;
    $user_dials = preg_split("/\#\*\#/",
        $_SESSION[$id]);


    $queryResult = $sth->queryLevel($id);
    $level = $queryResult['level'];
    //print_r($queryResult);


    $choiceArray = array();
    $choice = $user_data;
    //array_push($choiceArray, $choice);

   // $_SESSION['choice'] = $choiceArray;



    switch ($level){
            case 1:
            switch ($choice){
                case 1:
                    $msg = "Select a network provider\n";
                    $msg .= "1.MTN\n2.Vodafone\n3.AirtelTigo\n4.Glo".$back;
                    echo trueResponse($ussd_id, $msisdn, $user_data, $msg);
                    $_SESSION[$id] = $_SESSION[$id] . "#*#";

                    //promote level
                    $level = $static::$LEVEL_TWO;
                    $sth->updateLevel($id, $level);

                    array_push($choiceArray, $choice);
                    $_SESSION['choice'] = $choiceArray;

                    //session_destroy();
                    break;
                case 2:
                    $msg = "Select a Data Bundle\n";
                    $msg .= "1.MTN\n2.Vodafone\n3.AirtelTigo\n4.Glo\n5.LTE".$back;
                    trueResponse($ussd_id, $msisdn, $user_data, $msg);
                    $_SESSION[$id] = $_SESSION[$id] . "#*#";

                    //promote level
                    $level = $static::$LEVEL_TWO;
                    $sth->updateLevel($id, $level);
                    break;
                case 3:
                    //TODO push the values into static arrays?
                    $msg = "Select a Bill to Pay\n";
                    $msg .= "1.SunPower\n2.ECG\n3.NedCo\n4.DSTV/GOTv\n5.Water".$back;
                    trueResponse($ussd_id, $msisdn, $user_data, $msg);
                    $_SESSION[$id] = $_SESSION[$id] . "#*#";

                    //promote level
                    $level = $static::$LEVEL_TWO;
                    $sth->updateLevel($id, $level);
                    break;

                case 4:

                    $msg = "Merchants\n";
                    $msg .= "1.Airlines\n2.Other Merchants\n".$back;
                    trueResponse($ussd_id, $msisdn, $user_data, $msg);
                    $_SESSION[$id] = $_SESSION[$id] . "#*#";

                    //promote level
                    $level = $static::$LEVEL_TWO;
                    $sth->updateLevel($id, $level);
                    break;

                case 5:

                    $msg = "Contact Us\n";
                    $msg .= "Email: contact@broadspectrumltd.com\nWebsite: www.broadspectrumltd.com\n".$back;
                    trueResponse($ussd_id, $msisdn, $user_data, $msg);
                    $_SESSION[$id] = $_SESSION[$id] . "#*#";

                    //promote level
                    //$level = $static::$LEVEL_TWO;
                    //$sth->updateLevel($id, $level);
                    break;


                case 00:
                    print_r($_SESSION['choice']);
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
                        


                    case 00: //back
                        $previousChoice = $_SESSION['choice'][0];
                        if ($previousChoice == 1 || $previousChoice == 2 || $previousChoice == 3 || $previousChoice == 4 || $previousChoice == 5) {

                            $msg = "Welcome To TrendiPay\n1. Buy Airtime\n2. Buy Data Bundles\n3. Bill Payment\n4. Merchants\n5. Contact Us";
                            echo trueResponse($ussd_id, $msisdn, $user_data, $msg);
                            $_SESSION[$id] = $_SESSION[$id] . "#*#";

                            //demote level
                            $level = $static::$LEVEL_ONE;
                            $sth->updateLevel($id, $level);

                        }


                }
                break;

            case 3:
            echo "This is Level 1.1.1.3";
            break;

            case 4:
            //$breakswitch = 2;
                echo "Here and now";
                break;

            case 5:
                echo "This is level 1.2 ";



            break;

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
    return json_encode($resp);
}

function falseResponse($user_id, $msisdn, $user_data, $msg){
    $resp = array("USERID" => $user_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => false);
    echo json_encode($resp);
}


