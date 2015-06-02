<?php

/*
 * Following code will create a new user row
 * All user details are read from HTTP Post Request
 */

// array for JSON response
$response = array();
// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();
//echo json_encode($order_date);//selecting the inserted values back
// check for require fields
$json_results = json_decode($_GET["json"]);
$reportedIssue = $json_results->reportedIssue;
if ($reportedIssue->memberID && $reportedIssue->municipalityID && $reportedIssue->issuesID && $reportedIssue->latitude && $reportedIssue->longitude) {

    function randomRerNumber() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 6; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    $dateReported = date('Y-m-d H:i:s');
    $memberID = $reportedIssue->memberID;
    $reviews = $reportedIssue->reviews;
    $latitude = $reportedIssue->latitude;
    $longitude = $reportedIssue->longitude;
    $municipalityID = $reportedIssue->municipalityID;
    $issuesID = $reportedIssue->issuesID;
    $dateCreated = date('Y-m-d H:i:s');
    $refNumber = randomRerNumber();
    if ($municipalityID < 1) {
        $statusID = 2;
    } else {
        $statusID = 1;
    }

    $checkRefRepeat = mysql_query("SELECT * FROM reportedissue WHERE refNumber = '$refNumber'");
    if (mysql_num_rows($checkRefRepeat) > 0) {
        $refNumber = randomRerNumber();
    }
    // mysql inserting a new row
    $result = mysql_query("INSERT INTO reportedissue(dateReported,memberID,reviews,latitude,longitude,municipalityID,issuesID,dateCreated,refNumber) 
						   VALUES('$dateReported','$memberID', '$reviews','$latitude','$longitude','$municipalityID', '$issuesID','$dateCreated','$refNumber')");
    echo mysql_error();

    // check if row inserted or not
    if ($result) {
        // successfully inserted into database       
        //selecting the inserted values back
        $results = mysql_query("SELECT * FROM reportedissue WHERE dateReported = '$dateReported' 
		and memberID = '$memberID' and municipalityID = '$municipalityID' 
		and issuesID = '$issuesID' and refNumber = '$refNumber'");

        if (mysql_num_rows($results) > 0) {
            $row = mysql_fetch_array($results);
            $reportedIssueID = $row["reportedIssueID"];
            $statusReportedDate = date('Y-m-d H:i:s');
            $resultSRI = mysql_query("INSERT INTO statusreportedissue(statusID,reportedIssueID,statusReportedDate) 
						   VALUES('$statusID','$reportedIssueID', '$statusReportedDate')");
            echo mysql_error();

            if ($resultSRI) {
                $response["success"] = 1;
                $response["message"] = "Issue successfully reported.";
                $response["reportedIssueID"] = $reportedIssueID;

                echo json_encode($response);
            } else {
                $response["success"] = 0;
                $response["message"] = "Issue unsuccessfully reported.";

                echo json_encode($response);
            }
        } else {
            // failed to insert row
            $response["success"] = 0;
            $response["message"] = "Oops! An error occurred.";

            // echoing JSON response-25.666667, 28.333333
            echo json_encode($response);
        }
    } else {
        // failed to insert row
        $response["success"] = 0;
        $response["message"] = "Oops! An error occurred.";

        // echoing JSON response
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}
?>