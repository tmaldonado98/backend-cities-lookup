<?php
require "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

$new_list_name = $data['listName'];
$user_email = $data['userEmail'];


///This query just creates a new list with the name provided by the user.
$updateQuery = 
"UPDATE user_lists SET list_array = JSON_ARRAY_APPEND(
  list_array,
  '$',
  JSON_OBJECT('list_name', '$new_list_name')
  
  ) 
WHERE user_email = ?";

// "INSERT INTO user_lists (list_array) VALUES (?) WHERE user_email = ?";

// $arrayToInsert = json_encode(array('list_name' => $new_list_name));

$stmt = $conn -> prepare($updateQuery);
$stmt -> bind_param("s", $user_email);

if ($stmt->execute()) {
    header('Content-Type: application/json');

    $responseText = json_encode(array('list name' => $new_list_name, 'user email' => $user_email));
    echo json_encode($responseText);

} else {
    echo 'Error creating a new list. Please try again later.';
}


?>