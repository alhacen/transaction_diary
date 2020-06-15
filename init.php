<?php
session_start();
include "db_helper.php";
$userConfigured=$c['userConfigured'];
if($c['dbConfigured']=="1"){
	$sqlconnected=1;
	$dbConfigured=1;
}
if($c['userConfigured']==1){
	header("location: index.php");
}
if($userConfigured=="1"){

}
if(!isset($sqlconnected)){
	$sqlconnected=0;
}

if(isset($_POST['dbhost'])&&isset($_POST['dbpass'])&&isset($_POST['dbuser'])){
	$c['dbhost'] = $_POST['dbhost'];
	$c['dbuser'] = $_POST['dbuser'];
	$c['dbpass'] = $_POST['dbpass'];
	$sqlconn = new mysqli($c['dbhost'], $c['dbuser'], $c['dbpass']);
	if ($sqlconn->connect_error) {
		$_SESSION['msg']=array("danger",$conn->connect_error);
	  //die("Connection failed: " . $conn->connect_error);
	}
	$sqlconnected=1;
	// Create connection
	
	$conn = new mysqli($c['dbhost'], $c['dbuser'], $c['dbpass'], "transaction_diary");
	// Check connection
	if ($conn->connect_error) {
		if($conn->connect_error=="Unknown database 'transaction_diary'"){// if db not found
			//create database

			$sql = "CREATE DATABASE transaction_diary";
			if ($sqlconn->query($sql) === TRUE) {
			  //echo "Database created successfully";
				//$_SESSION['msg']=array("successful","Database created successfully");
			} else {
			  echo "Error creating database: " . $conn->error;
			}

		}
	}
	$conn = new mysqli($c['dbhost'], $c['dbuser'], $c['dbpass'], "transaction_diary");
	$sql = "
	CREATE TABLE IF NOT EXISTS `transactions` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `date_time` datetime NOT NULL,
	  `amount` int(10) NOT NULL,
	  `client_name` varchar(30) NOT NULL,
	  `client_phone` int(12) NOT NULL,
	  `transaction_method` varchar(10) NOT NULL,
	  `remark` varchar(50) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
	";
		if ($conn->query($sql) === TRUE) {
			$c['dbConfigured']=1;
			$c['dbname']="transaction_diary";
			$_SESSION['msg']=array("successful","Database Connected");
			updateCredentials();
		} else {
			  echo "Error creating database: " . $conn->error;
	}	
}elseif(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['cnfpassword'])){
	if((preg_match('/^[A-Za-z]{1}[A-Za-z0-9]{2,15}$/', $_POST['username'])) && (preg_match('/^[\w\d]{5,14}$/',$_POST['password'])) &&($_POST['password']==$_POST['cnfpassword'])){
		$_SESSION['msg']=array("successful","User Configured successfully");
		$c['username']=$_POST['username'];
		$c['password']=md5($_POST['password']);
		$c['userConfigured']="1";
		updateCredentials();
		header("location: index.php");
	}elseif(!(preg_match('/^[A-Za-z]{1}[A-Za-z0-9]{2,15}$/', $_POST['username']))){
		$_SESSION['msg']=array("danger","Username Should Contain 3-9 characters and Should not contain special characters");
	}elseif($_POST['password']!=$_POST['cnfpassword']){
		$_SESSION['msg']=array("danger","Password and Confirm Password did not match");
	}elseif(!(preg_match('/^[\w\d]{5,14}$/',$_POST['password']))){
		$_SESSION['msg']=array("danger","Password Should contain 6-15 characters");
	}else{
		$_SESSION['msg']=array("danger","Somthing Went Wrong");
	}
}
function updateCredentials(){
	global $c;
	$tmpstring="<?php\n\$c=array(";
			foreach ($c as $key => $value) {
				$tmpstring.="\"$key\"=>\"$value\",
				";	
			}
			$tmpstring.=")\n?>";
 			$myfile = fopen("db_credentials.php", "w") or die("Unable to open file!");
			fwrite($myfile, $tmpstring);
			fclose($myfile);
}
?>
<html>
<head>
  <title>Transaction Diary | init</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</head>
<body class="orange darken-1">
<div  class="row">

  <div id="login_panel" class="card col s12 l6 offset-l3 m8 offset-m2 center">
  	<?php
  	if(!$sqlconnected){
  		echo '
    <h4>Connect Mysql</4>
<div id="notification"></div>
<form action="" method="post">
    <p><input type="text" name="dbhost" placeholder="Database Host" style="width:50%" class="input-field"></p>
    <p><input type="text" name="dbuser" placeholder="Database Username" style="width:50%" class="input-field"></p>
    <p><input type="password" name="dbpass" placeholder="Database Password" style="width:50%" class="input-field"></p>

    <p><input type="submit" class="btn orange darken-1" value="Connect"></p>
</form>';
	}else if($sqlconnected && !$c['dbConfigured']){
		echo '<h4>Somthing went wrong while creating db2_tables(	)</4>
		<div id="notification"></div>
		
		';
	}else if($c['dbConfigured']){
			echo '<h4>Setup User Account</4>
		<div id="notification"></div>
		<form action="" method="post" id="setupAcc">
		    <p><input type="text" name="username" id="username" placeholder="Username	" style="width:50%" class="input-field"></p>
		    <p><input type="password" name="password" id="password" placeholder="Password" style="width:50%" class="input-field"></p>
		    <p><input type="password" name="cnfpassword" id="cnfpassword" placeholder="Confirm Password" style="width:50%" class="input-field"></p>

		    <p><input type="button" class="btn orange darken-1" value="Connect" onclick="submitForm()"></p>
		</form>
<script>

function submitForm(){
	userCheck = /\D+\D*?\w+$/gi;
	if(document.getElementById("cnfpassword").value==document.getElementById("password").value&&document.getElementById("password").value.length>6&&(/^([\w\-]{3,15})$/).test(document.getElementById("username").value)){
		document.getElementById("setupAcc").submit();
	}else if(!(/^([\w\-]{3,15})$/).test(document.getElementById("username").value)){
			alert("username	Should be between 3-15 characters and Should not contain any special characters");
	}else if(document.getElementById("cnfpassword").value.length<6||document.getElementById("cnfpassword").value.length>15){
		alert("password	Should be between 6-15 characters");
	}else{
		alert("Password	And Confirm	Password Should be same");
	}
}

</script>
		';
		
		
	}
?>
  </div>
</div>
</body>
</html>
<script>
<?php if(isset($_SESSION['msg'])){echo "var msg=".json_encode($_SESSION['msg']).";" ;} $_SESSION['msg']="";?>

  for(i=0;i<msg.length/2;i++){
    switch(msg[2*i]){
      case 'danger':
        color="#ffc107";
        break;
      case 'successful':
        color="#28a745";
        break;    
    }
document.getElementById("notification").innerHTML+='<div style="font-size:20px;padding:1px;color:'+color+';margin:1px;width:80%;margin-left:auto;margin-right:auto"><b>'+msg[2*i+1]+'</b></div>'
}
</script>