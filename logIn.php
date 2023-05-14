<?php
require "connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");


$credentials = json_decode(file_get_contents("php://input"), true);

    $email = $credentials['email'];
    $password = $credentials['password'];

    $sanitized_email = mysqli_real_escape_string($conn, htmlspecialchars($email));
    $sanitized_password = mysqli_real_escape_string($conn, htmlspecialchars($password));

    // echo 'User logging in with: ' . $sanitized_email . ' and ' . $sanitized_password;

    $selectQuery = "SELECT * FROM users WHERE email = ?";
    //  AND password = ?,       $sanitized_password


    $prepStatement = $conn -> prepare($selectQuery);

    $prepStatement -> bind_param("s", $sanitized_email);

    $prepStatement -> execute();

    $result = $prepStatement -> get_result();

    $userData = $result -> fetch_assoc();



    // , 'sessionId' => $response['session_id']
if ($userData == null) {
    echo 'false';
} else {
    $hashedPasswordFromDatabase = $userData['password'];

    $currentAccountArr = array('email' => $userData['email'], 'name' => $userData['name']);
    
    if (password_verify($sanitized_password, $hashedPasswordFromDatabase) && is_array($userData) && count($userData) > 0) {
        session_start();
        session_regenerate_id();

        $session_id = session_id();   

        if(setcookie('cookie_session_id', $session_id, time() + 3600, '/', '', false, true)) {

            $currentAccountArr['cookie'] = $session_id;

            header('Content-Type: application/json');
            echo json_encode($currentAccountArr);
        } 
        else {
            echo 'false';
        }
        

    }
    else {
        header('Content-Type: application/json');
        echo 'false';
    }
}

// $encodedInfo = array('email' => $sanitized_email, 'password' => $sanitized_password);
// echo json_encode($encodedInfo);


// {"email":"again@again.com","password":"again"}   ---- format of response.data object received from createAcct.php
?>