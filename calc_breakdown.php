<?php

/*
 * Following code will get single user details
 * A user is userIDentified by user userID (puserID)
 */

// array for JSON response
$response = array();
// check for require fields
// include db connect class

$json_results = json_decode($_GET["json"]);
// check for post data
if ($json_results->rand_note) {
    //$email = $json_results->email;
	$amount = $json_results->rand_note;
    $den = array(
        100, 50, 20, 10, 5, 2, 1, 0.50, 0.25, 0.10, 0.5
    );//100, 50, 20, 10, 5, 2, 1, 0.50, 0.25, 0.10, 0.5.
    $copy = $amount; //Making a copy of the amount
    $totalNotes = 0;
    $count = 0;
    for ($i = 0; $i < sizeOf($den); $i++) { //Since there are 9 different types of notes, hence we check for each note.
        $count = $amount / $den[$i]; // counting number of den[i] notes
        if ($count != 0) { //printing that denomination if the count is not zero
            echo $den[$i] . "\tx\t" . $count . "\t= " . ($den[$i] * $count)."\n";
        }
        $totalNotes = $totalNotes + $count; //finding the total number of notes
        $amount = $amount % $den[$i]; //finding the remaining amount whose denomination is to be found
    }
    echo "--------------------------------";
    echo "TOTAL\t\t\t= " . $copy; //printing the total amount
    echo "--------------------------------";
    echo "Total Number of Notes\t= " . $totalNotes;
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}
?>