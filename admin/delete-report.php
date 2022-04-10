<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
} else {
$reportid = $_GET['id'];
if($reportid == "") {
    header("HTTP/1.1 400 Bad Request");
        echo "400 Bad Request";
} else {
$data = file_get_contents('../reports.json');

$json_arr = json_decode($data, true);

$arr_index = array();
foreach ($json_arr as $key => $value) {
    if ($value['id'] == $reportid) {
        $arr_index[] = $key;
    }
}

foreach ($arr_index as $i) {
    unset($json_arr[$i]);
}

$json_arr = array_values($json_arr);

file_put_contents('../reports.json', json_encode($json_arr));
header("Location: reports.php");
}
include_once '../config.php';
}
?>