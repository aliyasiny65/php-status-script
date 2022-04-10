<?php
include "config.php";

$jsoniteminc = file_get_contents("incidentdata.json");

$objitemsinc = json_decode($jsoniteminc);
$findByIdinc = function($id) use ($objitemsinc) {
    foreach ($objitemsinc as $kategorifindinc) {
        if ($kategorifindinc->id == $id) return $kategorifindinc->id;
     }

    return false;
};

$dbcek = $conn->query("SELECT email FROM `users`");
$array = $dbcek->fetch_array();
$siteadmin = $array["email"];

$control = $findByIdinc(date("M/d/Y"));
//echo("$control");
$jsonitem = file_get_contents("data.json");

$objitems = json_decode($jsonitem);
$findById = function($id) use ($objitems) {
    foreach ($objitems as $kategorifind) {
        if ($kategorifind->id == $id) return $kategorifind->address;
     }

    return false;
};

if($control == "") {
    function fileWriteAppend(){
		$current_data = file_get_contents('incidentdata.json');
		$array_data = json_decode($current_data, true);
		$extra = array(
			 'id'               =>     date("M/d/Y"),
			 'history'          =>     "noinc",
			 'incname'          =>     "",
			 'progress'     =>     "",
			 'invst'     =>     "",
			 'completed'     =>     "",
             'completeddate'     =>     ""

		);
		$array_data[] = $extra;
		$final_data = json_encode($array_data);
		return $final_data;
}
if(file_exists('incidentdata.json'))
{
     $final_data=fileWriteAppend();
     if(file_put_contents('incidentdata.json', $final_data))
     {
          $message = "<label class='text-success'>AJAX job success</p>";
     }
}
}

$domain = $findById('category1');
$domain2 = $findById('category2');
$domain3 = $findById('category3');
if($domain == "") {
    $domain = $_SERVER['SERVER_NAME'];
};
if($domain2 == "") {
    $domain2 = $_SERVER['SERVER_NAME'];
};
if($domain3 == "") {
    $domain3 = $_SERVER['SERVER_NAME'];
};


function get_http_response_code($domain) {
  $headers = get_headers($domain);
  return substr($headers[0], 9, 3);
}

$get_http_response_code = get_http_response_code($domain);
if ( $get_http_response_code == 200  || $get_http_response_code == "") {
  //
} else {
  $msg = "Hello $sname Admin,\n Your Website Down\n Affected Category: Category 1\nPlease Check!";
  mail($siteadmin,"Your Website Down",$msg);
  $dataq = file_get_contents('incidentdata.json');
  $json_arr = json_decode($dataq, true);

    foreach ($json_arr as $key => $value) {
        if ($value['id'] == date("M/d/Y")) {
            $json_arr[$key]['history'] = "inc";
            $json_arr[$key]['invst'] = date('M d, h:i A', time());
        }
    }

    file_put_contents('incidentdata.json', json_encode($json_arr));

    $datao = file_get_contents('data.json');

    $json_arro = json_decode($datao, true);

    foreach ($json_arro as $keyo => $valueo) {
        if ($valueo['id'] == "category1") {
            $json_arro[$keyo]['status'] = "Not Operational";
        }
    }

    file_put_contents('data.json', json_encode($json_arro));
}

function get_http_response_code2($domain2) {
  $headers = get_headers($domain2);
  return substr($headers[0], 9, 3);
}

$get_http_response_code2 = get_http_response_code2($domain2);
if ( $get_http_response_code2 == 200 || $get_http_response_code2 == "") {
  //
} else {
  $msg2 = "Hello $sname Admin,\n Your Website Down\n Affected Category: Category 2\nPlease Check!";
  mail($siteadmin,"Your Website Down",$msg2);
  $dataq2 = file_get_contents('incidentdata.json');
  $json_arr2 = json_decode($dataq2, true);

    foreach ($json_arr2 as $key2 => $value2) {
        if ($value2['id'] == date("M/d/Y")) {
            $json_arr2[$key2]['history'] = "inc";
            $json_arr2[$key2]['invst'] = date('M d, h:i A', time());
        }
    }

    file_put_contents('incidentdata.json', json_encode($json_arr2));

    $datao2 = file_get_contents('data.json');

    $json_arro2 = json_decode($datao2, true);

    foreach ($json_arro2 as $keyo2 => $valueo2) {
        if ($valueo2['id'] == "category2") {
            $json_arro2[$keyo2]['status'] = "Not Operational";
        }
    }

    file_put_contents('data.json', json_encode($json_arro2));
}

function get_http_response_code3($domain3) {
    $headers = get_headers($domain3);
    return substr($headers[0], 9, 3);
  }
  
  $get_http_response_code3 = get_http_response_code2($domain3);
  if ( $get_http_response_code3 == 200 || $get_http_response_code3 == "") {
    //
  } else {
    $msg3 = "Hello $sname Admin,\n Your Website Down\n Affected Category: Category 3\nPlease Check!";
    mail($siteadmin,"Your Website Down",$msg3);
    $dataq3 = file_get_contents('incidentdata.json');
    $json_arr3 = json_decode($dataq3, true);
  
      foreach ($json_arr3 as $key3 => $value3) {
          if ($value3['id'] == date("M/d/Y")) {
              $json_arr3[$key3]['history'] = "inc";
              $json_arr3[$key3]['invst'] = date('M d, h:i A', time());
          }
      }
  
      file_put_contents('incidentdata.json', json_encode($json_arr3));
  
      $datao3 = file_get_contents('data.json');
  
      $json_arro3 = json_decode($datao3, true);
  
      foreach ($json_arro3 as $keyo3 => $valueo3) {
          if ($valueo3['id'] == "category3") {
              $json_arro3[$keyo3]['status'] = "Not Operational";
          }
      }
  
      file_put_contents('data.json', json_encode($json_arro3));
  }
?>
<html>
<body>
<script>
      function AjaxCallWithPromise() {
      return new Promise(function (resolve, reject) {
        const objXMLHttpRequest = new XMLHttpRequest();
 
        objXMLHttpRequest.onreadystatechange = function () {
            if (objXMLHttpRequest.readyState === 4) {
                if (objXMLHttpRequest.status == 200) {
                    resolve(objXMLHttpRequest.responseText);
                } else {
                    reject('Error Code: ' +  objXMLHttpRequest.status + ' Error Message: ' + objXMLHttpRequest.statusText);
                }
            }
        }
 
        objXMLHttpRequest.open('GET', 'index.php');
        objXMLHttpRequest.send();
      });
    }
 
    AjaxCallWithPromise().then(
        data => { console.log('') },
        error => { console.log(error) }
    );
</script>
</body>
</html>