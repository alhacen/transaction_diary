
<?php
session_start();
if(isset($_SESSION['logged'])&&$_SESSION['logged']==1){
    include'db_helper.php';
}else{header('location: index.php');exit();}
?>
<html>
<head>
  <title>Transaction Diary | Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script>
    /*
      irouter.js ver-0.2
      https://github.com/alhaqhassan/irouter.js
    */
(function(){function a(b){return document.getElementById(b)}function b(){(e.includes(location.hash.substr(2))||""==location.hash||"#!"==location.hash)&&(requested_page=location.hash.substr(2),""!=f&&"#!"!=f&&(a(f.substr(2)).style.display="none"),""==location.hash||"#!"==location.hash?location.hash="#!"+default_page:e.includes(requested_page)&&(a(requested_page).style.display="block"),null!=g[e.indexOf(requested_page)]&&c(g[e.indexOf(requested_page)],requested_page),f="#!"+requested_page),window.onhashchange=b}function c(b,c){if(prop=JSON.parse(b),c="insert-into"in prop?prop["insert-into"]:c,!h.includes(c)||h.includes(c)&&"false"==prop.cache){var e="get"in prop?"GET":"post"in prop?"POST":"other",f="loading-screen"in prop?a(prop["loading-screen"]).innerHTML:"Loading..";a(c).innerHTML=f,console.log(e),"other"!=e&&d(prop[e.toLowerCase()],e).then(function(b){a(c).innerHTML=b,h.push(c)}).catch(a=>console.error(a))}}function d(a="",b,c){return fetch(a,{method:b,mode:"cors",cache:"no-cache",credentials:"same-origin",headers:{"Content-Type":"application/x-www-form-urlencoded"},redirect:"follow",referrer:"no-referrer",body:c}).then(a=>a.text())}var e=[],f="",g=[],h=[],j="itmp_"+Math.random().toString(36).substring(7);document.addEventListener("DOMContentLoaded",function(){for(var a=document.getElementsByClassName("ipage"),c=0;c<a.length;c++)a[c].style.display="none",e[c]=a[c].id,g[c]=a[c].getAttribute("irouter");for(var d=document.getElementsByClassName("iloading-screen"),c=0;c<d.length;c++)d[c].style.display="none";default_page=document.getElementsByClassName("ipage-default")[0]===void 0?null:document.getElementsByClassName("ipage-default")[0].id;var f=document.createElement("DIV");f.id=j,document.body.appendChild(f),b()})})();
  </script>
</head>
<body class="orange darken-1">
 <ul id="slide-out" class="sidenav sidenav-fixed" >
    <li><a href="#!home">Home</a></li>
    <li><a href="#!history">History</a></li>
    <li><a href="#!setting">Setting</a></li>
    <li><a href="exe.php?logout">Logout</a></li>
  </ul>
  <div id="main">
    <div id="notification" class="card padding-15" style="margin:0 20 0 20px;display:none"></div>
    <div id="home" class="ipage ipage-default">
      <div id="content">
        <div class="card padding-15">
          <p>Add a new transaction</p>
          <div class="row">
          
          <form action="exe.php?add_new_transaction" method="post">
            <div class="col l2 input-field">
              <input id="amount" type="text" class="validate" name="amount">
              <label for="amount">Amount</label>
            </div>
            <div class="col l2 input-field">
              <input id="client_phone" type="text" class="validate" name="client_phone">
              <label for="client_phone">Phone no</label>
            </div>
            <div class="col l2 input-field">
              <select name="transaction_method">
                <option value="" disabled selected >Method</option>
                <option value="atm">ATM</option>
                <option value="aadhar">Aadhar</option>
              </select>
            </div>
            <div class="col l2 input-field">
              <input id="client_name" type="text" class="validate" name="client_name">
              <label for="client_name">Name</label>
            </div>
            <div class="col l2 input-field">
              <input id="remark" type="text" class="validate" name="remark">
              <label for="remark">Remark</label>
            </div>
            <div class="col l2 input-field">
              <input type="submit" class="btn orange darken-1"  value="save">
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
    <div id="history" class="ipage ">
      <div id="content">
        <div class="card padding-15">
          <p>History</p>
          <div class="row">
            <div class="col l4 input-field">
              <input id="q" type="text" class="validate" name="amount" autocomplete="off" onkeyup="search()">
              <label for="q">Search</label>
            </div>
            <div class="col l2 input-field">
              <input type="text" id="search_from" class="datepicker" placeholder="From" onchange="search()">
            </div>
            <div class="col l2 input-field">
              <input type="text" id="search_to" class="datepicker" placeholder="To" onchange="search()">
            </div>
            <div class="col l1 input-field">
              <select id="max_result" onchange="pagination_change(this.value)">
                <option value="" disabled >Maximum result</option>
                <option value="10" >10</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="1">No limit</option>
              </select>
            </div>
            <div class="col l1 input-field">
              <input type="button" class="btn" value="clear">
            </div>
          </div>
      <?php
      $date=date("Y-m-d");
      $amount_count=0;
      $transaction_count=0;
      $transactions = $db->query("SELECT * FROM transactions WHERE date_time LIKE '%{$date}%'")->fetchAll();
      foreach ($transactions as $transaction) {
        $amount_count+=$transaction['amount'];
        $transaction_count++;
      }
      ?>
      <h6>Total Transactions: <span id="transaction_count"><?php echo  $transaction_count; ?></span> ; total amount: <span id="amount_count"><?php echo $amount_count; ?></span></h6>
      <ul class="pagination center" id="pagination">
          
      </ul>
      <table class="highlight">
        <thead>
          <tr>
              <th>Amount</th>
              <th>Name</th>
              <th>By</th>
              <th>Mobile no</th>
              <th>Time</th>
          </tr>
        </thead>

        <tbody id="transactions_table">
        
        </tbody>
      </table>
      

        </div>
      </div>
    </div>
    <div id="setting" class="ipage ">
      <div id="content">
        <div class="card padding-15">
          change password
          <div class="row">          
            <div class="col l12 offset">
              <form action="exe.php?changePassword" method="post" id="changePasswordForm">
                <div class="col l3 m3 s12 input-field">
                  <input id="currentPassword" type="password" class="validate" name="currentPassword" value="">
                  <label for="currentPassword">Current Password</label>
                </div>
                <div class="col l3 m3 s12 input-field">
                  <input id="newPassword" type="password" class="validate" name="newPassword" value="">
                  <label for="newPassword">New Password</label>
                </div>
                <div class="col l3 m3 s12 input-field">
                  <input id="newCnfpassword" type="password" class="validate" name="newCnfpassword" value="">
                  <label for="newCnfpassword">Confirm Password</label>
                </div>
                <div class="col l1 m1 s12 input-field center">
                  <span><input type="button" class="btn" value="Change" onclick="changPwd()"></span>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div id="modal1" class="modal" style="max-height:90%;">
    <div class="modal-content">      
        <div class="row">          
          <div class="col l6 offset-l3">
          <form action="exe.php?edit_transaction" method="post" id="edit_form">
          <input type="hidden" name="transaction_id" id="transaction_id">
            <div class="col l12 input-field">
              <input id="edit_form_amount" type="text" class="validate" name="amount" value="-">
              <label for="edit_form_amount">Amount</label>
            </div>
            <div class="col l12 input-field">
              <input id="edit_form_client_phone" type="text" class="validate" name="client_phone" value="-">
              <label for="edit_form_client_phone">Phone no</label>
            </div>
            <div class="col l12 input-field">
              <select id="edit_form_transaction_method" name="transaction_method">
                <option value="" disabled selected >Method</option>
                <option value="atm">ATM</option>
                <option value="aadhar">Aadhar</option>
              </select>
            </div>
            <div class="col l12 input-field">
              <input id="edit_form_client_name" type="text" class="validate" name="client_name" value="-">
              <label for="edit_form_client_name">Name</label>
            </div>
            <div class="col l12 input-field">
              <input id="edit_form_remark" type="text" class="validate" name="remark" value="-">
              <label for="edit_form_remark">Remark</label>
            </div>
            <div class="col l12 input-field">
              <input id="password" type="password" class="validate" name="password" value="">
              <label for="password">password</label>
            </div>
            <div class="col l12 input-field">
              <input type="button" class="btn left red" onclick="delete_transaction()" value="delete">
              <input type="button" class="btn right " onclick="modify_transaction('modify')" value="save">
            </div>
          </form>
          </div>
          </div>
    </div>
  </div>  
</body>
</html>

<script>
function _(a){return document.getElementById(a);}
function pagination_change(max_result){
  _("pagination").innerHTML="";
   max_result=(max_result==1)?transactions.length:max_result// if max_result==1(max result) ax_result=transactions legnth
  for(i=1;i<transactions.length/max_result+1;i++){
    active=(i==1)?"active":"";
    _("pagination").innerHTML+='<input type="radio" name="pagination_btn" id="pagination_'+i+'"><label for="pagination_'+i+'" class="btn white black-text"  onclick="update_transaction_list('+max_result+","+(i-1)+')">'+i+'</label> '
  }
  update_transaction_list(max_result,0)
}
function update_transaction_list(max_result,b){
 
  transaction_count=0;
  amount_count=0;
  _("transactions_table").innerHTML=""
    for(i=1;i<=max_result;i++){
      try {
        if(transactions[(max_result*b+i-1)]['amount']!=undefined){transaction_count++;}
        amount_count+=parseInt(transactions[(max_result*b+i-1)]['amount']);
        _("transactions_table").innerHTML+="<tr onclick='location.href=\"#id="+(max_result*b+i-1)+"\";update_edit_form("+(max_result*b+i-1)+")' data-target='modal1' class=' modal-trigger cursor-pointer'><td>"+transactions[(max_result*b+i-1)]['amount']+"</td><td>"+transactions[(max_result*b+i-1)]['client_name']+"</td><td>"+transactions[(max_result*b+i-1)]['transaction_method']+"</td><td>"+transactions[(max_result*b+i-1)]['client_phone']+"</td><td>"+transactions[(max_result*b+i-1)]['date_time']+"</td></tr>";
      }catch(err) {
        console.log(err.message);
      }
    }
    update_transaction_count();
}
function update_transaction_count(){
  transaction_count=0;
  amount_count=0;
  for(i=0;i<transactions.length;i++){
    transaction_count++;
        amount_count+=parseInt(transactions[i]['amount']);
  }
  _("transaction_count").innerHTML=transaction_count;
  _("amount_count").innerHTML=amount_count;
}
function search(){
  _("q").value;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      _("transactions_table").innerHTML="";
      transactions = this.responseText;
      transactions=JSON.parse("["+transactions.substr(0,transactions.length-1)+"]");
      ///
      pagination_change(_("max_result").value);
      update_transaction_list(_("max_result").value,0);
    }
  };
  xhttp.open("GET", "search.php?q="+_("q").value+"&from="+_("search_from").value+"&to="+_("search_to").value, true);
  xhttp.send();
}
function delete_transaction() {
  if (confirm("Are you sure to delete")) {
    modify_transaction("delete");
  }
}
function modify_transaction(action){
  var xhttp = new XMLHttpRequest();
  action=(action=="modify")?"edit_transaction":"delete_transaction";
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if(this.responseText.trim()=="wrong password"){
        alert("Wrong Password");
      }else if(this.responseText.trim()=="task_done"){
        alert("Updated successfully");
        location.hash="#history";
        location.reload();
      }
    }
  };
  xhttp.open("POST", "exe.php?"+action, true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("amount="+_("edit_form_amount").value+"&client_name="+_("edit_form_client_name").value+"&transaction_method="+_("edit_form_transaction_method").value+"&client_phone="+_("edit_form_client_phone").value+"&remark="+_("edit_form_remark").value+"&password="+_("password").value+"&transaction_id="+_("transaction_id").value);
  xhttp.send();
}
function update_edit_form(a){
  tmp=transactions[a];
  _("transaction_id").value=(tmp['id']==undefined)?"":tmp['id'];
  _("edit_form_amount").value=(tmp['amount']==undefined)?"":tmp['amount'];
  _("edit_form_client_phone").value=(tmp['client_phone']==undefined)?"":tmp['client_phone'];
  _("edit_form_transaction_method").value=(tmp['transaction_method']==undefined)?"":tmp['transaction_method'];
  _("edit_form_client_name").value=(tmp['client_name']==undefined)?"":tmp['client_name'];
  _("edit_form_remark").value=(tmp['remark']==undefined)?"":tmp['remark'];
  var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems);
}
var transactions='<?php 
        $transactions = $db->query('SELECT * FROM transactions  ORDER BY id DESC')->fetchAll();
        foreach ($transactions as $transaction) {
          //echo "<tr onclick='location.href=\"#id=".$transaction['id']."\"' data-target='modal1' class=' modal-trigger cursor-pointer'><td>".$transaction['amount']."</td><td>".$transaction['client_name']."</td><td>".$transaction['transaction_method']."</td><td>".$transaction['client_phone']."</td><td>".$transaction['date_time']."</td></tr>";
          echo '{"id":"'.$transaction['id'].'","amount":"'.$transaction['amount'].'","client_name":"'.$transaction['client_name'].'","transaction_method":"'.$transaction['transaction_method'].'","client_phone":"'.$transaction['client_phone'].'","date_time":"'.$transaction['date_time'].'","remark":"'.$transaction['remark'].'"},';
        }
        ?>';
transactions=JSON.parse("["+transactions.substr(0,transactions.length-1)+"]");
pagination_change(10);

document.addEventListener('DOMContentLoaded', function() {
  format='yyyy-mm-dd';
  M.updateTextFields();
  var elems = document.querySelectorAll('select');
  var instances = M.FormSelect.init(elems);
  var elems = document.querySelectorAll('.modal');
  var instances = M.Modal.init(elems);
  var elems = document.querySelectorAll('.datepicker');
  var instances = M.Datepicker.init(elems,{format});
  });

<?php if(isset($_SESSION['msg'])){echo "var msg=".json_encode($_SESSION['msg']).";" ;} $_SESSION['msg']="";?>
setTimeout(function(){
  for(i=0;i<msg.length/2;i++){
    switch(msg[2*i]){
      case 'danger':
        color="#ffc107";
        break;
      case 'successful':
        color="#28a745";
        break;    
    }
    document.getElementById("notification").innerHTML+='<div style="border:solid 2px;padding:5px;color:'+color+';margin:1px;"><b>'+msg[2*i+1]+'</b></div>'
  }
}, 200);
if(msg){
  document.getElementById("notification").style.display="block";
}
setTimeout(function(){
var tmp =window.onhashchange
window.onhashchange=function(){
  document.getElementById("notification").style.display="none";
  tmp();  
}
}, 1000);

function changPwd(){
  if(document.getElementById("newCnfpassword").value==document.getElementById("newPassword").value&&(/^([\w\-]{6,15})$/).test(document.getElementById("currentPassword").value)&&(/^([\w\-]{6,15})$/).test(document.getElementById("newPassword").value)){
    document.getElementById("changePasswordForm").submit();
  }else if(!(/^([\w\-]{6,15})$/).test(document.getElementById("currentPassword").value)){
      alert("Wrong Current Password");
  }else if(!(/^([\w\-]{6,15})$/).test(document.getElementById("newPassword").value)){
    alert("password Should be between 6-15 characters");
  }else{
    alert("Password And Confirm Password Should be same");
  }
}
</script>
<style>
.cursor-pointer{cursor:pointer}
#home{display:none;}
#history{display:none;}
.padding-15{padding:15px}
#content{padding:25px}
#main{padding-left: 300px;}
@media only screen and (max-width : 992px) {
  #main {
    padding-left: 0;
  }
}
.input-field:focus {
   border-bottom: 1px solid #ffa726 !important;
   box-shadow: 0 1px 0 0 #ffa726 !important
 }
 #modal1{top:5% !important}
 input[type="radio"]:checked+label { 
    background-color:#fb8c00  !important;
}
#edit_form div{margin:7px}
#history>div>div{padding:25px;}
</style>