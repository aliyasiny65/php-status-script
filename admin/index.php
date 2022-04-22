<?php 

include '../config.php';

session_start();

error_reporting(0);

if (isset($_SESSION['email'])) {
    header("Location: /admin/panel.php");
}

if (isset($_POST['submit'])) {
	$email = $_POST['email'];
	$password = md5($_POST['password']);

	$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
	$result = mysqli_query($conn, $sql);
	if ($result->num_rows > 0) {
		$row = mysqli_fetch_assoc($result);
		$_SESSION['email'] = $row['email'];
		header("Location: /admin/panel.php");
	} else {
		echo "<script>alert('Password is not correct.')</script>";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="../assets/style.css">

	<title>Status Admin</title>
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
</head>
<body>
	<div class="container">
		<form action="" method="POST" class="login-email">
			<p class="login-text" style="font-size: 2rem; font-weight: 800;">Admin Panel</p>
			<div class="input-group">
				<input type="email" placeholder="Email" name="email" value="<?php echo $_POST['email']; ?>" required>
			</div>
			<div class="input-group">
				<input type="password" placeholder="Şifre" name="password" value="<?php echo $_POST['password']; ?>" required>
			</div>
			<div class="input-group">
				<button name="submit" class="btn">Login</button>
			</div>
		</form>
	</div>
</body>
</html>