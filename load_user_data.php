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
mysql_query("SET NAMES 'UTF8'");
$json_results = json_decode($_GET["json"]);
// check for post data
if ($json_results->communityMemberID) {

    $memberID = $json_results->communityMemberID;
    $municipalityID = $json_results->municipalityID;

    $response["reportedIssue"] = array();

    $resultRI = mysql_query("SELECT * FROM reportedissue WHERE memberID = '$memberID'");

    while ($rowRI = mysql_fetch_array($resultRI)) {
        $reportedIssue = array();
        $reportedIssue["reportedIssueID"] = $rowRI["reportedIssueID"];
        $reportedIssue["dateReported"] = $rowRI["dateReported"];
        $reportedIssue["memberID"] = $rowRI["memberID"];
        $reportedIssue["reviews"] = $rowRI["reviews"];
        $reportedIssue["latitude"] = $rowRI["latitude"];
        $reportedIssue["longitude"] = $rowRI["longitude"];
        $reportedIssue["municipalityID"] = $rowRI["municipalityID"];
        $reportedIssue["issuesID"] = $rowRI["issuesID"];
        $reportedIssue["dateCreated"] = $rowRI["dateCreated"];
        $reportedIssue["refNumber"] = $rowRI["refNumber"];

        $issuesID = $rowRI["issuesID"];
        $resultIssues = mysql_query("SELECT * FROM issues WHERE issuesID = '$issuesID'");
        $rowIssues = mysql_fetch_array($resultIssues);
        $reportedIssue["issueName"] = $rowIssues["issueName"];
        $reportedIssue["icon"] = $rowIssues["icon"];

        $reportedIssueID = $rowRI["reportedIssueID"];
        $resultSRI = mysql_query("SELECT * FROM statusreportedissue WHERE reportedIssueID = '$reportedIssueID'");

        $reportedIssue["statusReportedIssue"] = array();
        while ($rowSRI = mysql_fetch_array($resultSRI)) {
            $statusReportedIssue = array();
            $statusReportedIssue["statusReportedIssueID"] = $rowSRI["statusReportedIssueID"];
            $statusReportedIssue["statusReportedDate"] = $rowSRI["statusReportedDate"];
            $statusID = $rowSRI["statusID"];
            $resultS = mysql_query("SELECT * FROM status WHERE statusID = '$statusID'");
            $rowS = mysql_fetch_array($resultS);
            $statusReportedIssue["statusName"] = $rowS["statusName"];

            array_push($reportedIssue["statusReportedIssue"], $statusReportedIssue);
        }

        $resultII = mysql_query("SELECT * FROM issueimage WHERE reportedIssueID = '$reportedIssueID'");

        $reportedIssue["issueImage"] = array();
        while ($rowII = mysql_fetch_array($resultII)) {
            $issueImage = array();
            $issueImage["issueImageID"] = $rowII["issueImageID"];
            $issueImage["dateTaken"] = $rowII["dateTaken"];
            $issueImage["imageUrl"] = $rowII["imageUrl"];

            // push category into final response array
            //echo $booking["dateAttended"];
            array_push($reportedIssue["issueImage"], $issueImage);
        }
        array_push($response["reportedIssue"], $reportedIssue);
    }

    $response["issues"] = array();

    $resultsIssue = mysql_query("SELECT * FROM issues");
    while ($rowI = mysql_fetch_array($resultsIssue)) {
        $issues = array();
        $issues["issuesID"] = $rowI["issuesID"];
        $issues["name"] = $rowI["name"];
        $issues["icon"] = $rowI["icon"];

        // push category into final response array
        //echo $booking["dateAttended"];
        array_push($response["issues"], $issues);
    }

    $response["meeting"] = array();

    $resultsM = mysql_query("SELECT * FROM meeting WHERE municipalityID = '$municipalityID'");
    while ($rowM = mysql_fetch_array($resultsM)) {
        $meeting = array();
        $meeting["meetingID"] = $rowM["meetingID"];
        $meeting["topic"] = $rowM["topic"];
        $meeting["uploadflyerUrl"] = $rowM["uploadflyerUrl"];
        $meeting["meetingDate"] = $rowM["meetingDate"];
        $meeting["clerkID"] = $rowM["clerkID"];
        $meeting["municipalityID"] = $rowM["municipalityID"];
        $meeting["wardsID"] = $rowM["wardsID"];

        // push category into final response array
        //echo $booking["dateAttended"];
        array_push($response["meeting"], $meeting);
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