<?php

require_once 'DB.php';

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
$level = 1;

$sql = "INSERT into session_levels (sessionID, phoneNumber, level) VALUES ('$id', '$msisdn', '$level')";
$sth->insert($sql);