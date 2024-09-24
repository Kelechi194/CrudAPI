<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow_Method: Post');
header('Access-Control-Allow_Headers: Content-Type, Access-Control-Allow-Headers, Authorization. X-Request-with');

include('function.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];

if($requestMethod == 'GET'){
    if(isset($_GET['id'])){

        $event = getEvent($_GET);
        echo $event;
    }else{
        $eventList = getEventList();
        echo $eventList;
    }
    

}else{
    $data = [
        'status' => 405,
        'message' => $requestMethod. 'Method Not Allowed',
    ];
    header('HTTP/1.0 405 Method Not Allowed');
    echo json_encode($data);
}
