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

//
$jsoniteminc = file_get_contents("incidentdata.json");

$objitemsinc = json_decode($jsoniteminc);
//json ktgori
$findByIdinc = function($id) use ($objitemsinc) {
    foreach ($objitemsinc as $kategorifindinc) {
        if ($kategorifindinc->id == $id) return $kategorifindinc->history;
     }

    return false;
};
$findByincprgrs = function($id) use ($objitemsinc) {
  foreach ($objitemsinc as $prgrsinc) {
      if ($prgrsinc->id == $id) return $prgrsinc->progress;
   }

  return false;
};
$findByincinvst = function($id) use ($objitemsinc) {
  foreach ($objitemsinc as $invstinc) {
      if ($invstinc->id == $id) return $invstinc->invst;
   }

  return false;
};
$findByincnme = function($id) use ($objitemsinc) {
  foreach ($objitemsinc as $incnme) {
      if ($incnme->id == $id) return $incnme->incname;
   }

  return false;
};
$findByinccompltd = function($id) use ($objitemsinc) {
  foreach ($objitemsinc as $incompltd) {
      if ($incompltd->id == $id) return $incompltd->completed;
   }

  return false;
};
$findByinccompltddate = function($id) use ($objitemsinc) {
  foreach ($objitemsinc as $inccmpltdate) {
      if ($inccmpltdate->id == $id) return $inccmpltdate->completeddate;
   }

  return false;
};


if($format == "json") {
    header('Content-Type: application/json'); 
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
    } else if($data == "date") {
    $getdate = $_GET["date"];
    if($getdate == "") {
        header("HTTP/1.1 400 Bad Request");
        echo "400 Bad Request"; 
    } else {
       $getdateincstatusjson = $findByIdinc($getdate);
       $findincnamedatajson = $findByincnme("$getdate");
       $findincinvstdatajson = $findByincinvst("$getdate");
       $findincprgrsdescjson = $findByincprgrs("$getdate");
       $findinccompltddescjson = $findByinccompltd("$getdate");
       $findcompltddateincjson = $findByinccompltddate("$getdate");
       $arr1 = array (
               "day" => "$getdate",
               "incstatus" => $getdateincstatusjson,
               "incname" => $findincnamedatajson,
               "incinvestigatedate" => $findincinvstdatajson,
               "incprogressdescription" => $findincprgrsdescjson,
               "inccompleteddescription" => $findinccompltddescjson,
               "inccompleteddate" => $findcompltddateincjson
       );
       echo json_encode($arr1);
    };
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo "400 Bad Request";
    }
} else {
    if($data == "info") {
        echo "Server PHP Version: " . phpversion() . "<br>" . "Server Address: " . $_SERVER['SERVER_NAME'] . "<br>" . "Webserver: " . $_SERVER['SERVER_SIGNATURE'] . "<br>" . "Crypon Status Version: " . $version;
    } else if($data == "status") {
        echo "$sname Status Information: " . "<br>"  . $findById("category1") . ": " . $findBystatus("category1") . "<br>" . $findById("category2") . ": " . $findBystatus("category2") . "<br>" . $findById("category3") . ": " . $findBystatus("category3");
    } else if($data == "date") {
        $getdate = $_GET["date"];
        if($getdate == "") {
            header("HTTP/1.1 400 Bad Request");
            echo "400 Bad Request"; 
        } else {
            $getdateincstatus = $findByIdinc($getdate);
            if($getdateincstatus == "noinc") {
                echo("$getdate <br> No incidents reported.");
            } else if($getdateincstatus == "") {
                echo("$getdate <br> Not monitored.");
            } else {
                $findincnamedata = $findByincnme("$getdate");
                $findincinvstdata = $findByincinvst("$getdate");
                $findincprgrsdesc = $findByincprgrs("$getdate");
                $findinccompltddesc = $findByinccompltd("$getdate");
                $findcompltddateinc = $findByinccompltddate("$getdate");
                echo("$getdate <br> Incident Name:  $findincnamedata <br> Incident Investigating Date: $findincinvstdata <br> Progress Description: $findincprgrsdesc <br> Completed Description: $findinccompltddesc <br> Completed Date: $findcompltddateinc");
            }
        }
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo "400 Bad Request";
    }
}

?>