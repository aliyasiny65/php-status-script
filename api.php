<?php
include "config.php";
$data = $_GET["data"];
$format = $_GET["format"];
$jsonitem = file_get_contents("data.json");

$objitems = json_decode($jsonitem);
$findById = function($id) use ($objitems) {
    foreach ($objitems as $kategorifind) {
        if ($kategorifind->id == $id) return $kategorifind->name;
     }

    return false;
};
$findBystatus = function($id) use ($objitems) {
    foreach ($objitems as $statusfind) {
        if ($statusfind->id == $id) return $statusfind->status;
     }

    return false;
};
if($format == "json") {
    if($data == "info") {
        $arr = array (
            array(
                "id" => "Server PHP Version",
                "value" => phpversion(),
            ),
            array(
                "id" => "Server Address",
                "value" => $_SERVER['SERVER_NAME']
            ),
            array(
                "id" => "Webserver",
                "value" => $_SERVER['SERVER_SIGNATURE']
            ),
            array(
                "id" => "Crypon Status Version",
                "value" => $version
            )
        );
        echo json_encode($arr);
    } else if($data == "status") {
        $formatjsonstatus = file_get_contents("data.json");
        echo $formatjsonstatus;
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo "400 Bad Request";
    }
} else {
    if($data == "info") {
        echo "Server PHP Version: " . phpversion() . "<br>" . "Server Address: " . $_SERVER['SERVER_NAME'] . "<br>" . "Webserver: " . $_SERVER['SERVER_SIGNATURE'] . "<br>" . "Crypon Status Version: " . $version;
    } else if($data == "status") {
        echo "$sname Status Information: " . "<br>"  . $findById("category1") . ": " . $findBystatus("category1") . "<br>" . $findById("category2") . ": " . $findBystatus("category2") . "<br>" . $findById("category3") . ": " . $findBystatus("category3");
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo "400 Bad Request";
    }
}

?>