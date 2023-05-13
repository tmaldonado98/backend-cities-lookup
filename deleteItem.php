<?php
require "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_email = $data['userEmail'];
$list_index = $data['listIndex'];
$item_index = $data['listItemIndex'];

$deleteItem = 
"UPDATE user_lists SET list_array =
    JSON_REMOVE(
        list_array,
        '$[$list_index].place[$item_index]'
        )
    WHERE user_email = '$user_email'
";

$preparedStatement = $conn -> prepare($deleteItem);

// $preparedQuery -> bind_param('sss', $index, $updatedListName, $user_email);

if($preparedStatement -> execute()){
    header('Content-Type: application/json');
    echo 'true';
} else {
    echo 'false';
};

echo json_encode($list_index . $item_index);
 
?>