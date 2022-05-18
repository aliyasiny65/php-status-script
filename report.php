<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

include 'config.php';

error_reporting(0);

session_start();

$username = $_POST['username'];
$service = $_POST['service'];
$info = $_POST['info'];

$sql = "SELECT email FROM users";
$result = mysqli_query($conn, $sql);
$row = $result->fetch_assoc();
$getadminmail = $row["email"];

if($username == "") {
    $username = "Anonymous";
}
if (isset($_POST['submit'])) {
  include_once 'mailconfig.php';
    if($mailsendnotification == "SMTP") {
      $mail = new PHPMailer(true);
      $mail->isSMTP();
      $mail->Host = $smtphostname;
      $mail->SMTPAuth = true;
      $mail->Username = $smtpusernme;
      $mail->Password = $smtppswd;
      $mail->SMTPSecure = $smtpenc;
      $mail->Port = $smtpport;
      $mail->From = $smtpusernme;
      $mail->FromName = "$sname Status Admin";
      $mail->addAddress($getadminmail);
      $mail->isHTML(true);
      $mail->CharSet = 'UTF-8';
      $mail->Encoding = 'base64';
      $mail->Subject = "$service | 1 New Report!";
      $mail->Body = "Report Owner: $username\nAffected Service: $service\nReport Information: $info \n\n$sname Status";
      $mail->Timeout       =   60;
      $mail->SMTPKeepAlive = true;
    try {
    $mail->send();
    echo "";
    } catch (Exception $e) {
      $getlogfile = file_get_contents("admin/log.txt");
      $fp = fopen('admin/log.txt', 'w');
      fwrite($fp, "Mailer Error: ".$mail->ErrorInfo."  Date: ".date("M/d/Y H:i:s")."\n".$getlogfile);
      fclose($fp);
    }
} else if($mailsendnotification == "phpmail") {
  $subject = "$service | 1 New Report!";
  $txt = "Report Owner: $username\nAffected Service: $service\nReport Information: $info \n\n$sname Status";
  mail($getadminmail,$subject,$txt);
};
    //
    function fileWriteAppend(){
		$current_data = file_get_contents('reports.json');
		$array_data = json_decode($current_data, true);
		$extra = array(
			 'id'               =>     rand(1000, 9999),
       'name'          =>     $username = $_POST['username'],
			 'service'          =>     $service = $_POST['service'],
			 'info'          =>     $_POST['info'],
       'date'          =>     date("h:i A"),
		);
		$array_data[] = $extra;
		$final_data = json_encode($array_data);
		return $final_data;
}
if(file_exists('reports.json'))
{
     $final_data=fileWriteAppend();
     if(file_put_contents('reports.json', $final_data))
     {
          $message = "success";
     }
}

  $DELETE = "\$lastreport = '$lastreport';";
  $datao = file("./config.php");
  $out = array();
  foreach($datao as $line) {
  	if(trim($line) != $DELETE) {
  		$out[] = $line;
  	}
  }
  $fp = fopen("./config.php", "w+");
  flock($fp, LOCK_EX);
  foreach($out as $line) {
  	fwrite($fp, $line);
  }
  flock($fp, LOCK_UN);
  fclose($fp);

  $lastrapor = date("h:i");
  $data="\$lastreport = '$lastrapor';";
  $filecontent=file_get_contents('./config.php');
  "?>";
  $pos=strpos($filecontent, '?>');
  $filecontent=substr($filecontent, 0, $pos)."".$data."\r\n".substr($filecontent, $pos);
  file_put_contents("./config.php", $filecontent);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  <link rel="icon" type="image/png" href="./favicon.png" />
  <script src="assets/customjs.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
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

	<link rel="stylesheet" type="text/css" href="assets/style.css">

	<title>Report</title>
</head>
<body>
	<div class="container">
		<form action="" method="POST" class="login-email">
			<div class="input-group">
				<input type="text" placeholder="Your Name" name="username" value="<?php echo $_POST['username']; ?>" required>
			</div>
			<div class="input-group">
				<input type="text" placeholder="Affected Service" name="service" value="<?php echo $_POST['service']; ?>" required>
			</div>
			<div class="input-group">
				<input type="text" placeholder="Issue" name="info" value="<?php echo $_POST['info']; ?>" required>
            </div>
			<div class="input-group">
				<button name="submit" class="btn">Report</button>
			</div>
		</form>
	</div>
</body>
</html>