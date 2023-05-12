<?php
require "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_email = $data['userEmail'];
$index = $data['index'];

$deleteList = 
"UPDATE user_lists SET list_array =
    JSON_REMOVE(
        list_array,
        '$[$index]'
        )
    WHERE user_email = '$user_email'
";

$preparedDelete = $conn -> prepare($deleteList);

// $preparedQuery -> bind_param('sss', $index, $updatedListName, $user_email);

if($preparedDelete -> execute()){
    header('Content-Type: application/json');
    echo 'true';
} else {
    echo 'false';
};


?>