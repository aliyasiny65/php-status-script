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
    header("Location: mng-incidents.php");
}

include_once '../config.php';
$result = mysqli_query($conn,"SELECT * FROM employee");
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

    foreach ($json_arr as $key => $value) {
        if ($value['id'] == date("M/d/Y")) {
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

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="../style.css">

	<title>Update Incident</title>
</head>
<body>
	<div class="container">
		<form action="" method="POST" class="login-email">
			<p class="login-text" style="font-size: 2rem; font-weight: 800;">Update Incident</p>
            <p class="login-text" style="font-size: 14px; font-weight: 800;"><?php echo(date("M/d/Y")); ?></p>
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