<?php
require "connect.php";

$posted = json_decode(file_get_contents("php://input"), true);

$email = $posted['emailValue'];
$password = $posted['passwordValue'];
$name = $posted['nameValue'];

// Validate input and sanitize user input
$sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
$sanitized_password = filter_var($password, FILTER_SANITIZE_STRING);

// Hash the password
$hashed_password = password_hash($sanitized_password, PASSWORD_DEFAULT);

// Check if email exists
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $sanitized_email);
$stmt->execute();
$result = $stmt->get_result();


///Now create row for user in the user_lists table

$insertQuery = "INSERT INTO user_lists (list_name, user_email) VALUES (?, ?);";

$emptyList = json_encode(array());

$prepareListInsert = $conn -> prepare($insertQuery);

$prepareListInsert -> bind_param('ss', $emptyList, $sanitized_email);


////CONDITIONALLY EXECUTE QUERIES

if ($result->num_rows > 0) {
    // Email already exists
    echo 'Error creating a new account. Account already exists.';
} else {
    // Insert the new user into the database
    $stmt = $conn->prepare("INSERT INTO users (email, password, name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $sanitized_email, $hashed_password, $name);

    if ($stmt->execute()) {
        header('Content-Type: application/json');
        $encodedInfo = array('email' => $sanitized_email, 'password' => $hashed_password, 'name' => $name);
        echo json_encode($encodedInfo);
        $prepareListInsert -> execute();

    } else {
        echo 'Error creating a new account. Please try again later.';
    }


}

?>