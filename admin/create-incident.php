<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
}

include_once '../config.php';
//
$jsonitem = file_get_contents("../data.json");

$objitems = json_decode($jsonitem);
//json ktgori
$findById = function($id) use ($objitems) {
    foreach ($objitems as $kategorifind) {
        if ($kategorifind->id == $id) return $kategorifind->name;
     }

    return false;
};
if (isset($_POST['submit'])) {
    $ctg = $_POST['ctg'];
    $incnme = $_POST['incname'];
    $incinfo = $_POST['info'];
    if($ctg == $findById("category1")) {
        $ctg = "category1";
    } else if($ctg == $findById("category2")) {
        $ctg = "category2";
    } else if($ctg == $findById("category3")) {
        $ctg = "category3";
    }
    $data = file_get_contents('../incidentdata.json');

    $json_arr = json_decode($data, true);

    foreach ($json_arr as $key => $value) {
        if ($value['id'] == date("M/d/Y")) {
            $json_arr[$key]['history'] = "inc";
            $json_arr[$key]['incname'] = $incnme;
            $json_arr[$key]['progress'] = $incinfo;
            $json_arr[$key]['invst'] = date('M d, h:i A', time());
        }
    }

    file_put_contents('../incidentdata.json', json_encode($json_arr));


    //

    $datao = file_get_contents('../data.json');

    $json_arro = json_decode($datao, true);

    foreach ($json_arro as $keyo => $valueo) {
        if ($valueo['id'] == $ctg) {
            $json_arro[$keyo]['status'] = "Not Operational";
        }
    }

    file_put_contents('../data.json', json_encode($json_arro));
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="icon" type="image/png" href="../favicon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../assets/customjs.js"></script>
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
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="../style.css">

	<title>Create Incident</title>
    <style>
    .contact-form__input-group {display: block; margin-bottom: 25px; margin-top: 4px;margin-top: 4px;margin-top: 0.25rem;padding: 8px 16px;padding: 8px 16px;padding: 0.5rem 1rem;
    }

    .contact-form__label {margin-bottom: 25px; display: block;color: #414643;font-family: Lato, sans-serif;font-size: 75%;line-height: 1.125;
    }

    .contact-form__label--checkbox-group {margin-bottom: 25px; display: block;margin-right: 16px;margin-right: 16px;margin-right: 1rem;font-size: 75%;
    }

    .contact-form__label--checkbox,.contact-form__label--radio {margin-bottom: 25px; display: block;margin-left: 4px;margin-left: 4px;margin-left: 0.25rem;
    }

    .contact-form__input {margin-bottom: 25px; display: block; margin-top: 0;padding: 8px 12px;padding: 8px 12px;padding: 0.5rem 0.75rem;border: 1px solid #cdcfcf;width: 100%;font-family: 'Open Sans', sans-serif;font-size: 16px;font-size: 16px;font-size: 1rem;-webkit-transition: 150ms border-color linear;transition: 150ms border-color linear;
    }

    .contact-form__input--checkbox,.contact-form__input--radio {margin-bottom: 25px; display: block;width: auto;
    }

    .contact-form__input--checkbox~.contact-form__input--checkbox, .contact-form__input--radio~.contact-form__input--radio {display: block; margin-bottom: 25px; margin-left: 16px;margin-left: 16px;margin-left: 1rem;
    }

    .contact-form__input:focus,.contact-form__input:active {display: block; margin-bottom: 25px; border-color: #686a69;outline: 0;
    }
    </style>
</head>
<body>
	<div class="container">
		<form action="" method="POST" class="login-email">
			<p class="login-text" style="font-size: 2rem; font-weight: 800;">Create Incident</p>
			<div class="input-group">
				<input type="text" placeholder="Incident Name" name="incname" required>
			</div>
			<div class="input-group">
				<input type="text" placeholder="Incident Information" name="info" required>
			</div>
            <div class="input-group">
            <select class="contact-form__input contact-form__input--select" id="ctg" name="ctg" required>
                <option><?php echo($findById("category1")) ?></option>
                <option><?php echo($findById("category2")) ?></option>
                <option><?php echo($findById("category3")) ?></option>
            </select>
            </div>
			<div class="input-group">
				<button name="submit" class="btn">Create</button>
			</div>
		</form>
	</div>
</body>
</html>