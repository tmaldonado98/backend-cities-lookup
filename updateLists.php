<?php
require "connect.php";

$data = json_decode(file_get_contents("php://input"), true);

$city = $data['city'];
$country = $data['country'];
$user_email = $data['userEmail'];
$toList = $data['toList'];
$index = $data['index'];

///query to insert place to specific list
// [$toList]
$addPlace = 
"UPDATE user_lists SET list_array =
    JSON_SET(
        list_array,
        '$[$index]',
        JSON_MERGE_PRESERVE(
            -- list_array -> '$[$index]',
            JSON_EXTRACT(list_array, '$[$index]'),
            JSON_OBJECT(
                'place', JSON_ARRAY(
                    JSON_OBJECT('city', '$city', 'country', '$country')
                    )
                )
        )
  ) WHERE user_email = '$user_email'";


$prepareAdd = $conn -> prepare($addPlace);

// $prepareAdd -> bind_param('sss', $city, $country, $user_email);

if($prepareAdd -> execute()){
    header('Content-Type: application/json');
    echo 'true';
} else {
    echo 'false';
}

// echo 'Successfully added ' . $city . ', ' . $country . ' to the list: '. $toList;

// echo $country . $city . $user_email . $toList[0] . $index;

?>