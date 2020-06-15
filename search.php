<?php
session_start();
if(isset($_SESSION['logged'])&&$_SESSION['logged']==1){
    include'db_helper.php';
}else{header('location: index.php');exit();}
if(isset($_GET['q'])){
    $q=htmlspecialchars($_GET['q']);
    $limit=(isset($_GET['limit'])&&$_GET['limit']!="")?$_GET['limit']:10;
    $from=(isset($_GET['from'])&&$_GET['from']!="")?$_GET['from']:"";
    if(isset($_GET['to'])&&$_GET['to']!=""){
        $to=isset($_GET['to']);
        $to=strtotime($to);
        $to=strtotime("tomorrow");
        $to=date("Y-m-d", $to);
    }else{
        $to="";
    }
    if((isset($_GET['from'])&&$_GET['from']!="")&&(isset($_GET['to'])&&$_GET['to']=="")){
        $transactions = $db->query("SELECT * FROM transactions WHERE (client_name LIKE '%{$q}%' OR amount LIKE '%{$q}%' OR client_phone LIKE '%{$q}%' OR transaction_method LIKE '%{$q}%' OR remark LIKE '%{$q}%') AND (date_time LIKE '%{$from}%') ORDER BY id DESC")->fetchAll();
            foreach ($transactions as $transaction) {
            //echo "<tr onclick='location.href=\"#id=".$transaction['id']."\"' data-target='modal1' class=' modal-trigger cursor-pointer'><td>".$transaction['amount']."</td><td>".$transaction['client_name']."</td><td>".$transaction['transaction_method']."</td><td>".$transaction['client_phone']."</td><td>".$transaction['date_time']."</td></tr>";
            echo '{"id":"'.$transaction['id'].'","amount":"'.$transaction['amount'].'","client_name":"'.$transaction['client_name'].'","transaction_method":"'.$transaction['transaction_method'].'","client_phone":"'.$transaction['client_phone'].'","date_time":"'.$transaction['date_time'].'","remark":"'.$transaction['remark'].'"},';
        }        
    }elseif((isset($_GET['from'])&&$_GET['from']!="")&&(isset($_GET['to'])&&$_GET['to']!="")){
        $transactions = $db->query("SELECT * FROM transactions WHERE (client_name LIKE '%{$q}%' OR amount LIKE '%{$q}%' OR client_phone LIKE '%{$q}%' OR transaction_method LIKE '%{$q}%' OR remark LIKE '%{$q}%') AND (date_time >= '$from') AND (date_time <= '$to') ORDER BY id DESC")->fetchAll();
            foreach ($transactions as $transaction) {
            //echo "<tr onclick='location.href=\"#id=".$transaction['id']."\"' data-target='modal1' class=' modal-trigger cursor-pointer'><td>".$transaction['amount']."</td><td>".$transaction['client_name']."</td><td>".$transaction['transaction_method']."</td><td>".$transaction['client_phone']."</td><td>".$transaction['date_time']."</td></tr>";
        echo '{"id":"'.$transaction['id'].'","amount":"'.$transaction['amount'].'","client_name":"'.$transaction['client_name'].'","transaction_method":"'.$transaction['transaction_method'].'","client_phone":"'.$transaction['client_phone'].'","date_time":"'.$transaction['date_time'].'","remark":"'.$transaction['remark'].'"},';
        }
    }else{
        $transactions = $db->query("SELECT * FROM transactions WHERE client_name LIKE '%{$q}%' OR amount LIKE '%{$q}%' OR client_phone LIKE '%{$q}%' OR transaction_method LIKE '%{$q}%' OR remark LIKE '%{$q}%' ORDER BY id DESC")->fetchAll();
            foreach ($transactions as $transaction) {
            //echo "<tr onclick='location.href=\"#id=".$transaction['id']."\"' data-target='modal1' class=' modal-trigger cursor-pointer'><td>".$transaction['amount']."</td><td>".$transaction['client_name']."</td><td>".$transaction['transaction_method']."</td><td>".$transaction['client_phone']."</td><td>".$transaction['date_time']."</td></tr>";
            echo '{"id":"'.$transaction['id'].'","amount":"'.$transaction['amount'].'","client_name":"'.$transaction['client_name'].'","transaction_method":"'.$transaction['transaction_method'].'","client_phone":"'.$transaction['client_phone'].'","date_time":"'.$transaction['date_time'].'","remark":"'.$transaction['remark'].'"},';
        }
    }
}
?>