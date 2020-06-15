<?php
session_start();
//include"create_backup.php";
include "db_credentials.php";
if($c['userConfigured']==0){
  header("location: init.php");
  exit();
}
if(isset($_SESSION['logged'])&&$_SESSION['logged']==1){
  header("location: dashboard.php#!home");
}
if(isset($_POST['username'])&&isset($_POST['password'])){
  
  if(md5($_POST['password'])==$c['password']&&$_POST['username']==$c['username']){$_SESSION['logged']=1; header('location: dashboard.php#!home');}else{$err="1";}
}

?>
<html>
<head>
  <title>Transaction Diary</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</head>
<body class="orange darken-1">
<div  class="row">

  <div id="login_panel" class="card col s12 l4 offset-l4 m8 offset-m2 center">
    <h4>Transaction Diary</4>
<div id="notification"></div>
<form action="" method="post">
<?php if(isset($err)){echo "<span style='color:red;font-size:20px'>Wrong Credentials</span>";}?>
    <p><input type="text" name="username" placeholder="Usernme" style="width:50%" class="input-field"></p>
    <p><input type="password" name="password" placeholder="Password" style="width:50%" class="input-field"></p>
    <p><input type="submit" class="btn orange darken-1" value="login"></p>
</form>
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
<style>
body{backgound-color:red}
.input-field:focus {
   border-bottom: 1px solid #ffa726 !important;
   box-shadow: 0 1px 0 0 #ffa726 !important
 }
</style>