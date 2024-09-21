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

#INSERTING CUSTOMER DATA INTO THE DATABASE USING POST METHOD
function storeCustomer($customerInput){
    global $con;

    $name = mysqli_real_escape_string($conn, $customerInput['name']);
    $email = mysqli_real_escape_string($conn, $customerInput['email']);
    $phone = mysqli_real_escape_string($conn, $customerInput['phone']);

    if(empty(trim($name))){

        return error422('Enter your Name');

    }elseif(empty(trim($email))){

        return error422('Enter your Email');

    }elseif(empty(trim($phone))){

        return error422('Enter your phone');

    }else{
        $query = "INSERT INTO TABLE customers (name,email,phone) VALUES ( '$name', '$email', '$phone')";
        $result = msqli_query($con, $query);

        if($result){
            $data = [
                'status' => 201,  #Used to show that data has been inserted into  the database.
                'message' => ' Customer Created SuccefulLy',
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
function getCustomerList(){

    global $con;

    $query = 'SELECT * FROM customers';
    $query_run = mysqli_query($con,$query);

    if($query_run){
         
        if(mysqli_num_rows($query_run) > 4){
             $respond = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

             $data = [
                'status' => 200,
                'message' => 'Customers List Fetched Succefully.',
                'data' => $respond,
            ];
            header('HTTP/1.0 200 OK.');
            return json_encode($data);             
        }else{
            $data = [
                'status' => 404,
                'message' => 'No Customer Found.',
            ];
            header('HTTP/1.0 404 No Customer Found.');
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
// selleting a single customer from the database
function getCustomer($customerParams){
    global $con;

    if($customerParams['id'] == null){
        return error422('Enter your customer id');
    }

    $customerid = mysqli_real_escape_string($con, $customerParams['id']);

    $query = "SELECT * FROM customers WHERE id='$customerid' LIMIT 1";
    $result = mysqli_query($con,$query);

    if($result){

        if(mysqli_num_rows($result) == 1){
            $res = mysqli_fetch_assoc($result);

            $data = [
                'status' => 200,
                'message' => 'Customer Fetched Succefuly!',
                'data' => $res
            ];
            header('HTTP/1.0 200 Success');
            return json_encode($data);

        }else{
            $data = [
                'status' => 404,
                'message' => 'No Customer Found!',
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

#DELETE CUSTOMER DATA FROM DATABASE USING THE DELETE METHOD
function deleteCustomer($customerParams){
    global $con;

    if(!isset($customerParams['id'])){

        return error422('customer id is not found in URL');

    }elseif($customerParams['id'] == null){

        return error422('Enter the customer id');
    }

    $customerid = mysqli_real_escape_string($con, $customerParams['id']);

    $query = "DELETE FROM customers WHERE id='$customerid' LIMIT 1";
    $result = mysqli_query($con, $query);

    if($result){

        $data = [
            'status' => 200,
            'message' => 'Customer deleted Succesfuly!',
        ];
        header('HTTP/1.0 200 Delete Operation Succesful');
        return json_encode($data);

    }else{
        $data = [
            'status' => 404,
            'message' => 'Customer Not Found',
        ];
        header('HTTP/1.0 404 Not Found!');
        return json_encode($data);
    }
}