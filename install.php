<?php

if (isset($_POST['submit'])) {
    $servername = $_POST['servername'];
    $dbuser = $_POST['dbuser'];
    $dbpass = $_POST['dbpass'];
    $dbname = $_POST['dbname'];
    $sname = $_POST['sname'];
    $sdescription = $_POST['description'];
    
    $conn = mysqli_connect($servername, $dbuser, $dbpass, $dbname);
    
    $email = $_POST['mail'];
    $password = md5($_POST['passwd']);
    if($conn) {
	$nowdate = date("h:i");
        $configfile = fopen("config.php", "w") or die("Unable to create config file!");
        $data = "<?php \n\$sdescription = '$sdescription';\n\$sname = '$sname';\n\$server = '$servername';\n\$dbuser = '$dbuser';\n\$dbpass = '$dbpass';\n\$dbname = '$dbname';\n\$version = '2.0';\n\n\$matomo = 'disabled';\n\$ganalytics = 'disabled';\n\$conn = mysqli_connect(\$server, \$dbuser, \$dbpass, \$dbname);\n\n\$lastreport = '$nowdate';\n?>";
        fwrite($configfile, $data);
        fclose($configfile);
        $sqlinj = file_get_contents('dbtable.sql');
        $mysqli = new mysqli("$servername", "$dbuser", "$dbpass", "$dbname");
        $mysqli->multi_query($sqlinj);

        $incidentfile = fopen("incidentdata.json", "w") or die("Unable to create incident data file!");
        $datai = Array (
            "0" => Array (
                "id" => date("M/d/Y"),
                "history" => "noinc",
                "incname" => "",
                "progress" => "",
                "invst" => "",
                "completed" => "",
                "completeddate" => ""
            ),
            "1" => Array (
                "id" => date('M/d/Y',strtotime("-1 days")),
                "history" => "noinc",
                "incname" => "",
                "progress" => "",
                "invst" => "",
                "completed" => "",
                "completeddate" => ""
            ),
            "2" => Array (
                "id" => date('M/d/Y',strtotime("-2 days")),
                "history" => "noinc",
                "incname" => "",
                "progress" => "",
                "invst" => "",
                "completed" => "",
                "completeddate" => ""
            ),
            "3" => Array (
                "id" => date('M/d/Y',strtotime("-3 days")),
                "history" => "noinc",
                "incname" => "",
                "progress" => "",
                "invst" => "",
                "completed" => "",
                "completeddate" => ""
            )
        );
        $jsoninc = json_encode($datai);
        fwrite($incidentfile, $jsoninc);
        fclose($incidentfile);
        $sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
        if(mysqli_query($conn, $sql)){
            echo "Database records added.\n";
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }
        mysqli_close($conn);
        unlink('dbtable.sql');
        unlink('install.php');
        die("Installation Successful. You can upload favicon.png to the root directory of your server.");
    } else {
        die("Database data is incorrect!");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="assets/style.css">

	<title>Installation</title>
</head>
<body>
	<div class="container">
		<form action="" method="POST" class="login-email">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Database Installation</p>
			<div class="input-group">
				<input type="text" placeholder="Server" name="servername" required>
			</div>
			<div class="input-group">
				<input type="text" placeholder="Database User" name="dbuser" required>
			</div>
			<div class="input-group">
				<input type="password" placeholder="Database Password" name="dbpass" required>
            </div>
            <div class="input-group">
				<input type="text" placeholder="Database Name" name="dbname" required>
			</div>
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">User Installation and Site Settings</p>
            <div class="input-group">
				<input type="email" placeholder="Email" name="mail" required>
			</div>
            <div class="input-group">
				<input type="password" placeholder="Password" name="passwd" required>
            </div>
            <div class="input-group">
				<input type="text" placeholder="Site Name" name="sname" required>
            </div>
            <div class="input-group">
				<input type="text" placeholder="Site Description" name="description" required>
            </div>
			<div class="input-group">
				<button name="submit" class="btn">Install</button>
			</div>
		</form>
	</div>
</body>
</html>
