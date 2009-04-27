<?php
include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/customer.smarty.php");
include ("./lib/support/classes/ticket.php");
include ("./lib/support/classes/comment.php");
include ("./lib/epayment/includes/general.php");


if (! has_rights (ACX_SUPPORT)){
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");
	   die();
}


if($_GET["result"]=="success")
{
	$message = gettext("Ticket updated successfully");
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
  if (tep_not_null($action))
  {
    switch ($action)
    {
      case 'update':
		  $DBHandle  = DbConnect();
		  $instance_sub_table = Table::getInstance("cc_ticket", "*");
          $instance_sub_table -> Update_table($DBHandle, "status = '" . $_POST['status'] . "'","id = '" . $_GET['id'] . "'" );
		  $ticket = new Ticket($ticketID);
		  $ticket->insertComment($_POST['comment'],$_SESSION['card_id'],0);
          tep_redirect("A2B_ticket_view.php?"."id=".$_GET['id']."&result=success");
       case 'view_comment':
		  $DBHandle  = DbConnect();
		  $instance_sub_table = Table::getInstance("cc_ticket_comment", "*");
          $instance_sub_table -> Update_table($DBHandle, "viewed_cust = '0'","id = '" . $_POST['idc'] . "'" );
          tep_redirect("A2B_ticket_view.php?id=".$_GET['id']."#nav".$_POST['idc']);
           break;
        case 'view_ticket':
		  $DBHandle  = DbConnect();
		  $instance_sub_table = Table::getInstance("cc_ticket", "*");
          $instance_sub_table -> Update_table($DBHandle, "viewed_cust = '0'","id = '" . $_GET['id'] . "'" );
          tep_redirect("A2B_ticket_view.php?id=".$_GET['id']);
           break;
      break;
    }
  }




$ticket = new Ticket($ticketID);
$comments = $ticket->loadComments();


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
	<?php if($ticket->getViewed(0)){ ?>
	<tr>
		<td colspan="2" align="right">
		<br/>&nbsp;
		<input id="view_tick" class="view_ticket" type="checkbox" alt="<?php echo gettext("Click here to consider it like viewed"); ?>" style = "vertical-align:middle;" name="view" > <img  id="view_tick" src="<?php echo Images_Path ?>/eye.png"  class="view_ticket_icon" style = "vertical-align:middle;" border="0" title="<?php echo gettext("Click here to consider it like viewed"); ?>" alt="<?php echo gettext("Click here to consider it like viewed"); ?>" /> </td>
	</tr>
	<?php }else{
		?>
	<tr>
		<td colspan="2" >
		<br/> &nbsp;
	</tr>	
		
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

				<input class="form_input_button" type="submit" value="UPDATE"/>

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
  		 BY :  <?php echo $comment->getCreatorname(); ?>  </td>
  		 <td align="right"> <?php echo $comment->getCreationdate() ?> </td> 
  	</tr> 
	<tr>
		 <td colspan="2">&nbsp;  </td> 
	</tr> 
	<tr> 
		<td colspan="2"> <?php echo $comment->getDescription(); ?> </td> 
	</tr>  
	
	<?php if($comment->getViewed(0)){ ?>
	<tr>
		<td colspan="2" align="right">
		<br/>&nbsp;
		<input id="<?php echo $comment->getId(); ?>" class="view_comment" type="checkbox" alt="<?php echo gettext("Click here to consider it like viewed"); ?>" style = "vertical-align:middle;" name="view" > <img  id="<?php echo $comment->getId(); ?>" src="<?php echo Images_Path ?>/eye.png"  class="view_comment_icon" style = "vertical-align:middle;" border="0" title="<?php echo gettext("Click here to consider it like viewed"); ?>" alt="<?php echo gettext("Click here to consider it like viewed"); ?>" /> </td>
	</tr>
	<?php }else{
		?>
	<tr>
		<td colspan="2" >
		<br/> &nbsp;
	</tr>	
		
	<?php	
	} ?>
	</table> 
<?php	
 }

?>

<?php

$smarty->display('footer.tpl');



?>

<script type="text/javascript">
	
$(document).ready(function () {
	$('.view_comment').click(function () {
			$("#action").val('view_comment')
			$("#idc").val(this.id);
			$('form').submit();
	        });
	$('.view_comment_icon').click(function () {
			  $("#"+this.id+":checkbox").attr("checked", true);
			  $("#action").val('view_comment')
			  $("#idc").val(this.id);
			  $('form').submit();
	        });
	$('.view_ticket').click(function () {
			$("#action").val('view_ticket')
			$('form').submit();
	        });
	$('.view_ticket_icon').click(function () {
			  $('.view_ticket').attr("checked", true);
			  $("#action").val('view_ticket')
			  $('form').submit();
	        });
	                

	
       
});
</script>
