<?php
include "config.php";
$gethtaccess = file_get_contents("htaccess.txt");
$fp = fopen('.htaccess', 'w');
fwrite($fp, $gethtaccess);
fclose($fp);

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


$domain = $_SERVER['SERVER_NAME'];

function get_http_response_code($domain) {
  $headers = get_headers($domain);
  return substr($headers[0], 9, 3);
}

$get_http_response_code = get_http_response_code($domain);

if ( $get_http_response_code == 200 || $get_http_response_code == "") {
//
} else {
    $data = file_get_contents('incidentdata.json');
    $json_arr = json_decode($data, true);
      foreach ($json_arr as $key => $value) {
          if ($value['id'] == date("M/d/Y")) {
              $json_arr[$key]['history'] = "inc";
              $json_arr[$key]['invst'] = date('M d, h:i A', time());
          }
      }
      file_put_contents('incidentdata.json', json_encode($json_arr));
}
?>
<html>
<head>
<script src="assets/customjs.js"></script>
<?php
    //MATOMO ANALYTICS
    if($matomo == "enabled") {
      echo("
      <!-- Matomo -->
        <script>
          var _paq = window._paq = window._paq || [];
          _paq.push(['trackPageView']);
          _paq.push(['enableLinkTracking']);
          (function() {
            var u=\"$matomourl\";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '$matomoid']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
          })();
        </script>
        <!-- End Matomo Code -->
      ");
    } else {

    };

    //GOOGLE ANALYTICS
    if($ganalytics == "enabled") {
      echo("
      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src=\"https://www.googletagmanager.com/gtag/js?id=$ganalyticsid\"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '$ganalyticsid');
      </script>
      ");
    } else {

    };
    ?>
</head>
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