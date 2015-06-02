<?php

/*
 * Following code will get single user details
 * A user is userIDentified by user userID (puserID)
 */

// array for JSON response
$response = array();
// check for require fields
// include db connect class

require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();
$json_results = json_decode($_GET["json"]);
// check for post data
if ($json_results->municipalityID) {
    //$email = $json_results->email;
    $municipalityID = $json_results->municipalityID;

    $response["municipality"] = array();

    $results = mysql_query("SELECT * FROM municipality WHERE municipalityID = '$municipalityID'");

    while ($row = mysql_fetch_array($results)) {
        $municipality = array();
        $municipality["municipalityID"] = $row["municipalityID"];
        $municipality["municipalityName"] = $row["municipalityName"];
        $municipality["tell"] = $row["tell"];
        $municipality["longitude"] = $row["longitude"];
        $municipality["latitude"] = $row["latitude"];
        $municipality["address"] = $row["address"];
        $municipality["email"] = $row["email"];

        $resultMuni = mysql_query("SELECT * FROM town WHERE municipalityID = '$municipalityID'");

        $municipality["town"] = array();
        while ($rowMuni = mysql_fetch_array($resultMuni)) {
            $town = array();
            $town["townID"] = $rowMuni["townID"];
            $town["provinceID"] = $rowMuni["provinceID"];
            $town["name"] = $rowMuni["name"];
            $town["longitude"] = $rowMuni["longitude"];
            $town["latitude"] = $rowMuni["latitude"];

            // push category into final response array
            //echo $booking["dateAttended"];
            array_push($municipality["town"], $town);
        }

        $resultW = mysql_query("SELECT * FROM wards WHERE municipalityID = '$municipalityID'");

        $municipality["wards"] = array();
        while ($rowW = mysql_fetch_array($resultW)) {
            $wards = array();
            $wards["wardsID"] = $rowW["wardsID"];
            $wards["wardName"] = $rowW["wardName"];

            // push category into final response array
            //echo $booking["dateAttended"];
            array_push($municipality["wards"], $wards);
        }
        array_push($response["municipality"], $municipality);
    }

    // success
    $response["success"] = 1;
    $response["message"] = "data successfully returned";
    // users node
    // echoing JSON response
    echo json_encode($response);
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}
?>