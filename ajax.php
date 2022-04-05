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

$control = $findByIdinc(date("M/d/Y"));
//echo("$control");

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