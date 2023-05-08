<?php
require "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

$city = $data['city'];
$country = $data['country'];
$user_email = $data['userEmail'];
$toList = $data['toList'];

///query to insert place to specific list
$addPlace = 
"UPDATE user_lists SET list_array =
    JSON_ARRAY_APPEND(
        list_array,
        -- '$.$toList',
        JSON_ARRAY(
            JSON_OBJECT('city', '$city', 'country', '$country')
        )
  ) WHERE user_email = '$user_email'";

// JSON_SET(
//     list_array, 
//     '$.$toList', 

$prepareAdd = $conn -> prepare($addPlace);

// $prepareAdd -> bind_param('sss', $city, $country, $user_email);

if($prepareAdd -> execute()){
    header('Content-Type: application/json');
    echo 'Successfully added ' . $city . ', ' . $country . ' to the list: '. $toList;
} else {
    echo 'false';
}

// echo $country . $city . $user_email . $toList;

?>