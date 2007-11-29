<?php
session_name("UISIGNUP");
session_start();
require_once ("../lib/defines.php");
include ("../lib/smarty.php");
if (!$A2B->config["signup"]['enable_signup']) {
	exit;
}

if (!isset($_SESSION["date_mail"]) || (time()-$_SESSION["date_mail"]) > 60) {
	$_SESSION["date_mail"]=time();
} else {
	sleep(3);
	echo gettext("Sorry the confirmation email has been sent already, multi-signup are not authorized! Please wait 2 minutes before making any other signup!");
	exit();
}

if (!isset($_SESSION["cardnumber_signup"]) || strlen($_SESSION["cardnumber_signup"])<=1) {
	echo gettext("Error : No User Created.");
	exit();
}


$FG_DEBUG = 0;
//$link = DbConnect();
$DBHandle  = DbConnect();

$activatedbyuser = $A2B->config["signup"]['activatedbyuser'];

$lang_code = $_SESSION["language_code"];
if(!$activatedbyuser) {
	$QUERY = "SELECT mailtype, fromemail, fromname, subject, messagetext, messagehtml FROM cc_templatemail WHERE mailtype='signup' and id_language = '$lang_code'";
	//echo "<br>User is Not Activated";
} else {
	$QUERY = "SELECT mailtype, fromemail, fromname, subject, messagetext, messagehtml FROM cc_templatemail WHERE mailtype='signupconfirmed' and id_language = '$lang_code'";
	//echo "<br>User is Already Activated";		
}
$res = $DBHandle -> Execute($QUERY);
$num = 0;	
if ($res)
	$num = $res -> RecordCount();

if (!$num)
{
	if(!$activatedbyuser){
	$QUERY = "SELECT mailtype, fromemail, fromname, subject, messagetext, messagehtml FROM cc_templatemail WHERE mailtype='signup' and id_language = 'en'";
	//echo "<br>User is Not Activated";
	}else{
		$QUERY = "SELECT mailtype, fromemail, fromname, subject, messagetext, messagehtml FROM cc_templatemail WHERE mailtype='signupconfirmed' and id_language = 'en'";
		//echo "<br>User is Already Activated";		
	}

	$res = $DBHandle -> Execute($QUERY);
	$num = 0;	
	if ($res)
		$num = $res -> RecordCount();
	
	if (!$num) {
		echo "<br>".gettext("Error : No email Template Found");
		exit();
	}
}


for($i=0;$i<$num;$i++) {
	$listtemplate[] = $res->fetchRow();
}

list($mailtype, $from, $fromname, $subject, $messagetext, $messagehtml) = $listtemplate [0];

if ($FG_DEBUG == 1) {
	echo "<br><b>mailtype : </b>$mailtype</br><b>from:</b> $from</br><b>fromname :</b> $fromname</br><b>subject</b> : $subject</br><b>ContentTemplate:</b></br><pre>$messagetext</pre></br><hr>";
}

$QUERY = "SELECT username, lastname, firstname, email, uipass, credit, useralias, loginkey FROM cc_card WHERE username='".$_SESSION["cardnumber_signup"]."' ";
$res = $DBHandle -> Execute($QUERY);
$num = 0;	
if ($res)
	$num = $res -> RecordCount();

if (!$num) {
	echo "<br>".gettext("Error : No such user found in database");
	exit();
}

for($i=0;$i<$num;$i++) {
	$list[] = $res->fetchRow();
}

if ($FG_DEBUG == 1) echo "</br><b>BELOW THE CARD PROPERTIES </b><hr></br>";
$keepmessagetext = $messagetext;

foreach ($list as $recordset)
{
	$messagetext = $keepmessagetext;
	
	list($username, $lastname, $firstname, $email, $uipass, $credit, $cardalias, $loginkey) = $recordset;
	
	if ($FG_DEBUG == 1) echo "<br># $username, $lastname, $firstname, $email, $uipass, $credit, $cardalias #</br>";
	
	$messagetext = str_replace('$name', $lastname, $messagetext);
	//$message = str_replace('$username', $form->getValue('username'), $messagetext);
	$messagetext = str_replace('$card_gen', $username, $messagetext);
	$messagetext = str_replace('$password', $uipass, $messagetext);
	$messagetext = str_replace('$cardalias', $cardalias, $messagetext);
	$messagetext = str_replace('$cardalias', $cardalias, $messagetext);
	$messagetext = str_replace('=$loginkey', "=$loginkey", $messagetext);
	$messagetext = str_replace('$loginkey', "=$loginkey", $messagetext);

	a2b_mail($recordset[3], $subject, $messagetext, $from, $fromname);
	if ($FG_DEBUG == 1) echo "</br><b>".$recordset[3]."<br> subject=$subject,<br> messagetext=$messagetext,</br> em_headers=$em_headers</b><hr></br>";
}


$smarty->display('signup_header.tpl');

?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function sendto(action, record, field_inst, instance){
  document.myForm.form_action.value = action;
  document.myForm.sub_action.value = record;
  document.myForm.elements[field_inst].value = instance;
  myForm.submit();
}

function sendtolittle(direction){
  myForm.action=direction;
  myForm.submit();

}

//-->
</script>


<blockquote>
    <div align="center"><br></br>
	 <font color="#FF0000"><b><?php echo gettext("SIGNUP CONFIRMATION"); ?></b></font><br>
		  <br></br>
		  
	<?php if (!activatedbyuser){ ?>
		  <?php echo $list[0][2]; ?> <?php echo $list[0][1]; ?>, <?php echo gettext("Thank you for registering with us !");?><br>
		  <?php echo gettext("An email confirming your information has been sent to"); ?> <b><?php echo $list[0][3]; ?></b><br></br>
			<h3>
			  <?php echo gettext("Your cardnumber is "); ?> <b><font color="#00AA00"><?php echo $list[0][0]; ?></font></b><br></br></br>
			  <?php echo gettext("To login to your account :"); ?></br>
			  <?php echo gettext("Your card alias (login) is "); ?> <b><font color="#00AA00"><?php echo $list[0][6]; ?></font></b><br>
			  <?php echo gettext("Your password is "); ?> <b><font color="#00AA00"><?php echo $list[0][4]; ?></font></b><br>
			</h3>	  
	<?php }else{ ?>
		<?php echo $list[0][2]; ?> <?php echo $list[0][1]; ?>, <?php echo gettext("thank you for registering with us!");?><br>
		<?php echo gettext("An activation email has been sent to"); ?> <b><?php echo $list[0][3]; ?></b><br></br>
	
	<?php } ?>
		
</div>
</blockquote>      
<br>
  <br><br><br><br><br>
<?php
	$smarty->display('signup_footer.tpl');
?>
