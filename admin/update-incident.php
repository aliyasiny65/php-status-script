<?php 
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
}

$jsoniteminc = file_get_contents("../incidentdata.json");

$objitemsinc = json_decode($jsoniteminc);
//json ktgori
$findByIdinc = function($id) use ($objitemsinc) {
    foreach ($objitemsinc as $kategorifindinc) {
        if ($kategorifindinc->id == $id) return $kategorifindinc->history;
     }

    return false;
};
if($findByIdinc(date("M/d/Y")) == "noinc") {
    if($findByIdinc(date('M/d/Y',strtotime("-1 days"))) == "noinc") {
    header("Location: mng-incidents.php");
    };
}

include_once '../config.php';
//
$jsonitem = file_get_contents("../data.json");

$objitems = json_decode($jsonitem);

$compltdinfo = $_POST['completedinfo'];
$findBystatus = function($id) use ($objitems) {
    foreach ($objitems as $statusfind) {
        if ($statusfind->id == $id) return $statusfind->status;
     }

    return false;
};
if($findBystatus("category1") != "Operational") {
    $ctg = "category1";
} else if($findBystatus("category2") != "Operational") {
    $ctg = "category2";
} else if($findBystatus("category3") != "Operational") {
    $ctg = "category3";
}
$findById = function($id) use ($objitems) {
    foreach ($objitems as $kategorifind) {
        if ($kategorifind->id == $id) return $kategorifind->name;
}

    return false;
};
if (isset($_POST['submit'])) {
    $datao = file_get_contents('../data.json');

    $json_arro = json_decode($datao, true);

    foreach ($json_arro as $keyo => $valueo) {
        if ($valueo['id'] == $ctg) {
            $json_arro[$keyo]['status'] = "Operational";
        }
    }
    file_put_contents('../data.json', json_encode($json_arro));

    //


    $data = file_get_contents('../incidentdata.json');

    $json_arr = json_decode($data, true);
    if($findByIdinc(date("M/d/Y")) == "noinc") {
        $gunq = date("M/d/Y");
    } else if($findByIdinc(date('M/d/Y',strtotime("-1 days"))) == "noinc") { 
        $gunq = date('M/d/Y',strtotime("-1 days"));
    }
    foreach ($json_arr as $key => $value) {
        if ($value['id'] == $gunq) {
            $json_arr[$key]['completed'] = $compltdinfo;
            $json_arr[$key]['completeddate'] = date('M d, h:i A', time());
        }
    }

    file_put_contents('../incidentdata.json', json_encode($json_arr));
};
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="../style.css">
    <script src="../assets/customjs.js"></script>
	<title>Update Incident</title>
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
	<div class="container">
		<form action="" method="POST" class="login-email">
			<p class="login-text" style="font-size: 2rem; font-weight: 800;">Update Incident</p>
            <p class="login-text" style="font-size: 14px; font-weight: 800;"><?php
             if(date("M/d/Y") == "noinc") {
                 echo date('M/d/Y',strtotime("-1 days"));
            } else {
                echo date('M/d/Y');
            } ?></p>
            <p class="login-text" style="font-size: 14px; font-weight: 800;"><?php echo("Affected Services: "); echo($findById($ctg)); ?></p>
			<div class="input-group">
				<input type="text" placeholder="Incident Completed Information" name="completedinfo" value="<?php echo $_POST['completedinfo']; ?>" required>
			</div>
			<div class="input-group">
				<button name="submit" class="btn">Update</button>
			</div>
		</form>
	</div>
</body>
</html>