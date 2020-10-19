<?php

//<!--read incoming data-- >

$request = file_get_contents("php://input");



//<!--convert the json object($request) to a PHP object.-->

$data = json_decode($request, true);

print_r($data);

$message = null;
foreach ($data as $datum) {
    $message .=  $datum."\n";

}
echo $message;
echo strlen($message);

//echo $data['USERID'];

?>
