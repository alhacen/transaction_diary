<?php
session_start();
if(isset($_SESSION['logged'])&&$_SESSION['logged']==1){
        include'db_helper.php';
}else{header('location: index.php');exit();}
$time =date("Y-m-d H:i");
if(isset($_GET['add_new_transaction'])){
    if(!is_numeric($_POST['amount'])||!is_numeric($_POST['client_phone']) ){
        header('Location: dashboard.php#!home');
        $_SESSION['msg']=array("danger","Record can't added");
        echo "err";
        exit();
    }
    $client_name=htmlspecialchars($_POST['client_name']);
    $client_phone=htmlspecialchars($_POST['client_phone']);
    $amount=htmlspecialchars($_POST['amount']);
    $transaction_method=htmlspecialchars($_POST['transaction_method']);
    $remark=htmlspecialchars($_POST['remark']);
    $insert = $db->query('INSERT INTO transactions (amount,date_time,client_name,client_phone,transaction_method,remark) VALUES (?,?,?,?,?,?)',$amount,$time,$client_name,$client_phone,$transaction_method,$remark);
    header('Location: dashboard.php#!home');
    $_SESSION['msg']=array("successful","Record added successfully");    
}elseif(isset($_GET['edit_transaction'])){
    if(md5($_POST['password'])=="fe546279a62683de8ca334b673420696"){
        if(!is_numeric($_POST['amount']) ){
            $_SESSION['msg']=array("danger","Record can't added");
            echo "err";
            exit();
        }
        $transaction_id=htmlspecialchars($_POST['transaction_id']);
        $client_name=htmlspecialchars($_POST['client_name']);
        $client_phone=htmlspecialchars($_POST['client_phone']);
        $amount=htmlspecialchars($_POST['amount']);
        $transaction_method=htmlspecialchars($_POST['transaction_method']);
        $remark=htmlspecialchars($_POST['remark']);
        $insert = $db->query('UPDATE transactions SET amount=?,date_time=?, client_name=?,client_phone=?,transaction_method=?,remark=? WHERE id=?',$amount,$time,$client_name,$client_phone,$transaction_method,$remark,$transaction_id);
        //UPDATE MyGuests SET lastname='Doe' WHERE id=2
        echo "task_done";
    }else{echo "wrong password";}
}elseif(isset($_GET['delete_transaction'])){
    if(md5($_POST['password'])=="fe546279a62683de8ca334b673420696"){
        if(!is_numeric($_POST['amount']) ){
            $_SESSION['msg']=array("danger","Record can't added");
            echo "err";
            exit();
        }
        $transaction_id=htmlspecialchars($_POST['transaction_id']);
        $insert = $db->query('DELETE from transactions WHERE id=?',$transaction_id);
        echo "task_done";
    }else{echo "wrong password";}

}elseif(isset($_GET['logout'])){
    header("location: index.php");
    setcookie(session_name(), '', 100);
    session_unset();
    session_destroy();
    $_SESSION = array();
}elseif(isset($_GET['changePassword'])&&isset($_POST['newPassword'])&&isset($_POST['currentPassword'])){
    if((preg_match('/^[\w\d]{5,14}$/',$_POST['newPassword'])) &&(md5($_POST['currentPassword'])==$c['password'])){
        $c['password']=md5($_POST['newPassword']);
        updateCredentials();
        $_SESSION['msg']=array("successful","Password Changed"); 
        header("location: dashboard.php#!setting");
    }elseif((md5($_POST['currentPassword'])!=$c['password'])){
        $_SESSION['msg']=array("danger","Wrong Current Password");
        header("location: dashboard.php#!setting");
    }elseif(!(preg_match('/^[\w\d]{5,14}$/',$_POST['newPassword']))){
        $_SESSION['msg']=array("danger","Password Should Contain 6-15 characters");
        header("location: dashboard.php#!setting");
    }else{
        $_SESSION['msg']=array("danger","somthing went wrong"); 
        header("location: dashboard.php#!setting");
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
