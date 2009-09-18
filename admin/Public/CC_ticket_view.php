<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");
include ("../lib/support/classes/ticket.php");
include ("../lib/support/classes/comment.php");
include ("../lib/epayment/includes/general.php");

if (!has_rights(ACX_SUPPORT)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array (
	'result',
	'action',
	'status',
	'id',
	'idc',
	'comment'
));

if ($result == "success") {
	$message = gettext("Ticket updated successfully");
}


if (!empty($id)) {
	$ticketID = $id;
} else {
	exit (gettext("Ticket ID not found"));
}




if (tep_not_null($action)) {
	switch ($action) {
		case 'update' :
			$DBHandle = DbConnect();
			$instance_sub_table = new Table("cc_ticket", "*");
			$instance_sub_table->Update_table($DBHandle, "status = '" . $status . "'", "id = '" . $id . "'");
			$ticket = new Ticket($ticketID);
			$ticket->insertComment($comment, $_SESSION["admin_id"], 1);
			tep_redirect("CC_ticket_view.php?" . "id=" . $id . "&result=success");
			break;
	}
}

$ticket = new Ticket($ticketID);
$comments = $ticket->loadComments();

$ticket = new Ticket($ticketID);
$comments = $ticket->loadComments();
$DBHandle = DbConnect();
$instance_sub_table = new Table("cc_ticket", "*");
    if($ticket->getViewed(2)){
	$instance_sub_table->Update_table($DBHandle, "viewed_admin = '0'", "id = '" . $id . "'");
    }
$instance_sub_table = new Table("cc_ticket_comment", "*");
foreach ($comments as $comment) {
    if($comment->getViewed(2)){
	$instance_sub_table->Update_table($DBHandle, "viewed_admin = '0'", "id = '" . $comment->getId() . "'");
    }
}

$smarty->display('main.tpl');

?>
<table class="epayment_conf_table">
	<tr class="form_head">
	    <td ><font color="#FFFFFF"><?php echo gettext("TICKET: "); ?></font><font color="#FFFFFF"><b><?php echo $ticket->getTitle();  ?></b></font></td>
	    <td align="center" ><font color="#FFFFFF"><?php echo gettext("Number"); ?> : </font><font color="Red"> <?php echo $ticket->getId(); ?></font></td>
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
	<?php if($ticket->getViewed(2)){ ?>
	<tr>
		<td colspan="2" align="right">
		<strong style="font-size:8px; color:#B00000; "> &nbsp;NEW&nbsp;</strong>
		</td>
	</tr>
	<?php }else{
		?>
		
	<?php	
	} ?>
	<tr >
    <td colspan="2" align="center"><br/><font color="Green"><b><?php echo $message ?></b></font></td>
	</tr>
</table>

<br/>


  <form action="<?php echo $PHP_SELF.'?id='.$ticket->getId(); ?>" method="post" >
 	<input id="action" type="hidden" name="action" value="update"/>
	<input id="idc" type="hidden" name="idc" value=""/>
	<table class="epayment_conf_table">
	  <?php
	   $return_status = Ticket::getPossibleStatus($ticket->getStatus(),true);
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

				<input class="form_input_button" type="submit" value="<?php echo gettext("UPDATE"); ?>"/>

			 </td>
		</tr>

	</table>
  </form>


<?php

foreach ($comments as $comment)
 {
 ?>
 	<br/>
 	<table id="nav<?php echo $comment->getId(); ?>" class="epayment_conf_table">
  	<tr class="form_head"> 
  		<td>
  		 <?php echo gettext("BY"); ?> :  <?php echo $comment->getCreatorname(); ?>  </td>
  		 <td align="right"> <?php echo $comment->getCreationdate() ?> </td> 
  	</tr> 
	<tr>
		 <td colspan="2">&nbsp;  </td> 
	</tr> 
	<tr> 
		<td colspan="2"><pre><?php echo $comment->getDescription(); ?></pre> </td>
	</tr>  
	
	<?php if($comment->getViewed(2)){ ?>
	<tr>
		<td colspan="2" align="right">
		<br/>&nbsp;
		<strong style="font-size:8px; color:#B00000; "> &nbsp;NEW&nbsp;</strong> </td>
	</tr>
	<?php }else{
		?>
		
	<?php	
	} ?>
	</table> 
<?php	
 }

?>

<?php

$smarty->display('footer.tpl');



?>
