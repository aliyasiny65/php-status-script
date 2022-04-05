<?php 

include 'config.php';

error_reporting(0);

session_start();

$username = $_POST['username'];
$service = $_POST['service'];
$info = $_POST['info'];

if($username == "") {
    $username = "Anonymous";
}
if (isset($_POST['submit'])) {
    function fileWriteAppend(){
		$current_data = file_get_contents('reports.json');
		$array_data = json_decode($current_data, true);
		$extra = array(
			 'id'               =>     rand(1000, 9999),
             'name'          =>     $username = $_POST['username'],
			 'service'          =>     $service = $_POST['service'],
			 'info'          =>     $_POST['info'],
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
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="style.css">

	<title>Report</title>
</head>
<body>
	<div class="container">
		<form action="" method="POST" class="login-email">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Report an Issue</p>
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