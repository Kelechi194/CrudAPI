<?php

require '../inc/db.php';

#PASSING ERROR OR HANDELING ERROR DISPLAY
function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header('HTTP/1.0 422 Unproccesable Entity');
    echo json_encode($data);
    exit();
}

#INSERTING event DATA INTO THE DATABASE USING POST METHOD
function storeEvent($eventInput){
    global $con;

    $title = mysqli_real_escape_string($conn, $eventInput['title']);
    $description = mysqli_real_escape_string($conn, $eventInput['description']);
    $date = mysqli_real_escape_string($conn, $eventInput['date']);
    $location = mysqli_real_escape_string($conn, $eventInput['location']);

    if(empty(trim($title))){

        return error422('Enter your Title');

    }elseif(empty(trim($description))){

        return error422('Enter your Description');
        
    }elseif(empty(trim($date))){

        return error422('Enter your Date');

    }elseif(empty(trim($location))){

        return error422('Enter your Location');

    }else{
        $query = "INSERT INTO TABLE event (title,description,date,location) VALUES ( '$title', '$description', '$date','$location')";
        $result = msqli_query($con, $query);

        if($result){
            $data = [
                'status' => 201,  #Used to show that data has been inserted into  the database.
                'message' => ' Event Created SuccefulLy',
            ];
            header('HTTP/1.0 201 Created');
            echo json_encode($data);
        }else{
            $data = [
                'status' => 405,
                'message' => 'Method Not Allowed',
            ];
            header('HTTP/1.0 405 Method Not Allowed');
            echo json_encode($data);
        }
    }
}


#GETTING COSTOMER DATA FROM DATABASE WITH GET METHOD
function getEvenList(){

    global $con;

    $query = 'SELECT * FROM event';
    $query_run = mysqli_query($con,$query);

    if($query_run){
         
        if(mysqli_num_rows($query_run) > 4){
             $respond = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

             $data = [
                'status' => 200,
                'message' => 'event List Fetched Succefully.',
                'data' => $respond,
            ];
            header('HTTP/1.0 200 OK.');
            return json_encode($data);             
        }else{
            $data = [
                'status' => 404,
                'message' => 'No event Found.',
            ];
            header('HTTP/1.0 404 No event Found.');
            return json_encode($data);
        }
    }else{
        $data = [
            'status' => 500,
            'message' => 'Internal Sever Error!',
        ];
        header('HTTP/1.0 500 Internal Sever Error!');
        return json_encode($data);
    }

}
// selleting a single event from the database
function getEven($eventParams){
    global $con;

    if($eventParams['id'] == null){
        return error422('Enter your event id');
    }

    $eventid = mysqli_real_escape_string($con, $eventParams['id']);

    $query = "SELECT * FROM event WHERE id='$eventid' LIMIT 1";
    $result = mysqli_query($con,$query);

    if($result){

        if(mysqli_num_rows($result) == 1){
            $res = mysqli_fetch_assoc($result);

            $data = [
                'status' => 200,
                'message' => 'event Fetched Succefuly!',
                'data' => $res
            ];
            header('HTTP/1.0 200 Success');
            return json_encode($data);

        }else{
            $data = [
                'status' => 404,
                'message' => 'No event Found!',
            ];
            header('HTTP/1.0 404 Not Found!');
            return json_encode($data);
        }

    }else{
        $data = [
            'status' => 500,
            'message' => 'Internal Sever Error!',
        ];
        header('HTTP/1.0 500 Internal Sever Error!');
        return json_encode($data);
    }

}

#DELETE event DATA FROM DATABASE USING THE DELETE METHOD
function deleteEvent($eventParams){
    global $con;

    if(!isset($eventParams['id'])){

        return error422('event id is not found in URL');

    }elseif($eventParams['id'] == null){

        return error422('Enter the event id');
    }

    $eventid = mysqli_real_escape_string($con, $eventParams['id']);

    $query = "DELETE FROM event WHERE id='$eventid' LIMIT 1";
    $result = mysqli_query($con, $query);

    if($result){

        $data = [
            'status' => 200,
            'message' => 'event deleted Succesfuly!',
        ];
        header('HTTP/1.0 200 Delete Operation Succesful');
        return json_encode($data);

    }else{
        $data = [
            'status' => 404,
            'message' => 'event Not Found',
        ];
        header('HTTP/1.0 404 Not Found!');
        return json_encode($data);
    }
}