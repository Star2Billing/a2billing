<?php
include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/customer.smarty.php");
include ("./lib/support/classes/ticket.php");
include ("./lib/support/classes/comment.php");


if (! has_rights (ACX_SUPPORT)){
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");
	   die();
}



if (isset($_GET["id"]))
{
    $ticketID = $_GET["id"];
}
else
{
    exit(gettext("Ticket ID not found"));
}


$action = (isset($_POST['action']) ? $_POST['action'] : '');
  if (!is_null($action))
  {
    switch ($action)
    {
      case 'update':
		  $DBHandle  = DbConnect();
		  $instance_sub_table = new Table("cc_ticket", "*");
          $instance_sub_table -> Update_table($DBHandle, "status = '" . $_POST['status'] . "'","id = '" . $_GET['id'] . "'" );
		  $ticket = new Ticket($ticketID);
		  $ticket->insertComment($_POST['comment'],1,false);
         $message = gettext("Ticket updated successfully");
      break;
    }
  }




$ticket = new Ticket($ticketID);



$smarty->display('main.tpl');

?>
<table class="epayment_conf_table">
	<tr class="form_head">
	    <td ><font color="#FFFFFF"><?php echo gettext("TICKET: "); ?></font><font color="#FFFFFF"><b><?php echo $ticket->getTitle();  ?></b></font></td>
	    <td align="center" ><font color="#FFFFFF">Number : </font><font color="Red"> <?php echo $ticket->getId(); ?></font></td>
	</tr>
	<tr>
		<td>
		&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="2">
		 <font style="font-weight:bold; " ><?php echo gettext("BY : "); ?></font>  <?php echo $ticket->getCreatorname();  ?>

		</td>
	</tr>
	<tr>
		<td>
		 <font style="font-weight:bold; " ><?php echo gettext("PRIORITY : "); ?></font>  <?php echo $ticket->getPriorityDisplay();  ?>
		 </td>
		<td>
		<font style="font-weight:bold; " ><?php echo gettext("DATE : "); ?></font>  <?php echo $ticket->getCreationdate();  ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		 <font style="font-weight:bold; " ><?php echo gettext("COMPONENT : "); ?></font>  <?php echo $ticket->getComponentname();  ?>

		</td>
	</tr>
	<tr>
		<td colspan="2">
		<br/>
		<font style="font-weight:bold; " ><?php echo gettext("DESCRIPTION : "); ?></font>  <br/> <?php echo $ticket->getDescription();  ?></td>
	</tr>
	<tr >
    <td colspan="2" align="center"><font color="Green"><b><?php echo $message ?></b></font></td>
	</tr>
</table>

<br/>


  <form action="<?php echo $PHP_SELF.'?id='.$ticket->getId(); ?>" method="post" >
 	<input type="hidden" name="action" value="update"/>

	<table class="epayment_conf_table">
	  <?php

	   $return_status = Ticket::getPossibleStatus($ticket->getStatus(),false);
	   if(!is_null($return_status)) {

	   	 ?>
		<tr>
			<td colspan="2">	<font style="font-weight:bold; " ><?php echo gettext("STATUS : "); ?></font>

			<select name="status"  >

			 <?php


			 foreach ($return_status as $value)
				 {
				 	if($ticket->getStatus()==$value["id"]){

				 		echo '<option selected "value="'.$value["id"] .'"> '.$value["name"].'</option> ' ;

				 	}else{
				 		echo '<option value="'.$value["id"] .'"> '.$value["name"].'</option> ' ;
				 	}
				 }

			  ?>

			</select>
			</td>
		</tr>
	 <?php } ?>

		<tr>
			<td colspan="2"><font style="font-weight:bold; " ><?php echo gettext("COMMENT : "); ?>

			 </td>
		</tr>

		<tr>
			<td colspan="2" align="center">
			 <textarea class="form_input_textarea" name="comment" cols="100" rows="10"></textarea>

			 </td>
		</tr>
		<tr>
			<td colspan="2" align="right">

				<input class="form_input_button" type="submit" value="UPDATE"/>

			 </td>
		</tr>

	</table>
  </form>


<?php
$comments = $ticket->loadComments();
foreach ($comments as $comment)
 {
 	echo '<br/> <table class="epayment_conf_table"> ' ;
  	echo '  <tr class="form_head"> <td> BY : '.$comment->getCreatorname().'   </td> <td align="right"> '. $comment->getCreationdate()  .' </td> </tr>  ';
	echo ' <tr> <td>&nbsp;  </td> </tr> <tr> <td colspan="2"> '.$comment->getDescription() .' </td> </tr>  ';

 	echo ' </table> ';
 }

?>

<?php

$smarty->display('footer.tpl');



?>


