<?php
require "connect.php";

$accountData = json_decode(file_get_contents("php://input"), true);

$user_email = $accountData['email'];


// $retrieve = "SELECT * FROM user_lists JOIN users ON user_lists.user_email = users.email WHERE user_lists.user_email = ?";

$retrieve = "SELECT list_array FROM user_lists WHERE user_email = ?";

$preparedStatement = $conn -> prepare($retrieve);

$preparedStatement -> bind_param('s', $user_email);

$preparedStatement -> execute();

$queryResult = $preparedStatement -> get_result();

$responseData = mysqli_fetch_assoc($queryResult);

if (mysqli_num_rows($queryResult) == 0) {
    echo 'false';
} else {
    echo json_encode($responseData);
}

?>