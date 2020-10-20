<?php
/**
 * This is a sample USSD code with session management.
 * It has only two screens.The purpose is to help developers get started with their USSD application and session management.
 */

//<!--read incoming data-- >

$request = file_get_contents("php://input");



//<!--convert the json object($request) to a PHP object.-->

$data = json_decode($request, true);


/**
 * set your custom session id and start session for the incoming request.
 * Note!!
 * The only unique parameter is the msisdn.
 * Set session id with the msisdn in order to track the session
 */

session_id(md5($data['MSISDN']));
//print_r(session_id());
session_start();

//<!--Get All Incoming Request Parameters-- >

$ussd_id = $data['USERID'];
$msisdn = $data['MSISDN'];
$user_data = $data['USERDATA'];
$msgtype = $data['MSGTYPE'];
$id = session_id();

//<!--Subsequent dials-- >

if (isset($_SESSION[$id]) and $msgtype == false) {
    $_SESSION[$id] = $_SESSION[$id] . $user_data;
    $user_dials = preg_split("/\#\*\#/",
        $_SESSION[$id]);
    print_r($user_dials);
    //determine which level of the menu user is at

    $level = count($user_dials);
    echo $level;

    switch ($level) {

        case 2:
            switch ($user_dials[1]) {
                case 1:
                    $msg = "1.MTN\n2.Vodafone\n3.AirtelTigo\n4.Glo";
                    $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
                    echo json_encode($resp);
                    $_SESSION[$id] = $_SESSION[$id] . "#*#";

                    //session_destroy();
                    break;
                case 2:
                    $msg = "Hello1 " . $user_dials[1] . ", Your initial dial was " . $user_dials[0] . "\nInputs were successfully stored and passed on to this screen.\nHappy Coding :)";
                    $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
                    echo json_encode($resp);
                    session_destroy();
                    break;
                case 3:
                    $msg = "Hello3 " . $user_dials[1] . ", Your initial dial was " . $user_dials[0] . "\nInputs were successfully stored and passed on to this screen.\nHappy Coding :)";
                    $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
                    echo json_encode($resp);
                    session_destroy();
                    break;
                case 4:
                    $msg = "Hello " . $user_dials[1] . ", Your initial dial was " . $user_dials[0] . "\nInputs were successfully stored and passed on to this screen.\nHappy Coding :)";
                    $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
                    echo json_encode($resp);
                    session_destroy();
                    break;
                default:
                    $msg = "Hello " . $user_dials[1] . ", Your initial dial was " . $user_dials[0] . "\nInputs were successfully stored and passed on to this screen.\nHappy Coding :)";
                    $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
                    echo json_encode($resp);
                    session_destroy();
            }

            break;

        case 3:
            if ($user_dials[1] == 1) {
                $msg = "Enter Recipient's Number";
                $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
                echo json_encode($resp);
                $_SESSION[$id] = $_SESSION[$id] . "#*#";

            }

            break;

        case 4:
            if ($user_dials[1] == 1) {
                //TODO run phone number validation (create external function)
                $phoneNumber = $user_dials[3];
                $msg = "Enter Amount";
                $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
                //echo  "PhoneNumber. ".$phoneNumber;
                echo json_encode($resp);
                $_SESSION[$id] = $_SESSION[$id] . "#*#";

            }

            break;

        case 5:
            //TODO run extra if branch to cater for incorrect phone number
            if ($user_dials[1] == 1) {
                //TODO run fetchPayments option (create external function)
                $phoneNumber = $user_dials[4];
                $msg = "Please select your payment option\n1.MTN\n2.Vodafone\n3.AirtelTigo\n4.G-Money";
                $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
                echo json_encode($resp);
                $_SESSION[$id] = $_SESSION[$id] . "#*#";

            }

            break;

        case 6:
            //TODO run charge API
            //use callback success or fail to indicate whether person will receive push
            $msg = "Thank you. you'll receive a push";
            $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
            echo json_encode($resp);
            $_SESSION[$id] = $_SESSION[$id] . "#*#";
            session_destroy();

            break;




    }

} // Initial dial

else {

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
    $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
    echo json_encode($resp);
 }

header('Content-type: application/json');

