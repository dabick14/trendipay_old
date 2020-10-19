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
    $msg = "Hello " . $user_dials[1] . ", Your initial dial was " . $user_dials[0] . "\nInputs were successfully stored and passed on to this screen.\nHappy Coding :)";
    $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => false);
    echo json_encode($resp);
    session_destroy();
} // Initial dial

else {

    //<!--To reinitial session variable in case the use cancelled initial screen-- >
    if (isset($_SESSION[$id]) and $msgtype == true) {
        session_unset();
    }

    /**
     * Stores user inputs using sessions.
     * You may also store user inputs in a database
     */

    $_SESSION[$id] = $user_data . "#*#";

// Responds to request. MSG variable will be displayed on the user's screen

    $msg = "Welcome to TrendiPay\nThis is to help you get started with session/data management\nEnter your name please";
    $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
    echo json_encode($resp);
 }

header('Content-type: application/json');

?>