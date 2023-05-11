<?php
require "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_email = $data['userEmail'];
$toList = $data['toList'];
$updatedListName = $data['updatedListName'];
$index = $data['index'];

$update = 
"UPDATE user_lists SET list_array =
    JSON_SET(
        list_array,
        '$[$index].list_name',
        '$updatedListName'
        )
    WHERE user_email = '$user_email'
";

$preparedQuery = $conn -> prepare($update);

// $preparedQuery -> bind_param('sss', $index, $updatedListName, $user_email);

if($preparedQuery -> execute()){
    header('Content-Type: application/json');
    echo 'true';
} else {
    echo 'false';
};


?>