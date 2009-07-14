<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_INVOICING)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array('id','addrate','delrate'));

if (empty($id)) {
	Header ("Location: A2B_entity_package.php?atmenu=package&section=12");
}

$table_pack = new Table("cc_package_offer ","*");
$pack_clauses = "id = $id";
$result_pack=$table_pack ->Get_list(DbConnect(), $pack_clauses);

if(!is_array($result_pack)|| sizeof($result_pack)!=1){
	Header ("Location: A2B_entity_package.php?atmenu=package&section=12");
}

if(isset($addrate) && is_numeric($addrate)) {
        $DBHandle = DbConnect();
        $add_rate_table = new Table("cc_package_rate", "*");
        $fields = " package_id , rate_id";
        $values = " $id , $addrate";
        $add_rate_table->Add_table($DBHandle, $values, $fields);
	Header ("Location: A2B_package_manage_rates.php?id=$id");
}

if(isset($delrate) && is_numeric($delrate)) {
	$DBHandle = DbConnect();
        $del_rate_table = new Table("cc_package_rate", "*");
        $CLAUSE = " package_id = " . $id . " AND rate_id = $delrate";
        $del_rate_table->Delete_table($DBHandle, $CLAUSE);
	Header ("Location: A2B_package_manage_rates.php?id=$id");
}

$smarty->display('main.tpl');

//load rates
$table_rates = new Table("cc_package_rate JOIN cc_ratecard ON cc_ratecard.id = cc_package_rate.rate_id LEFT JOIN cc_prefix ON cc_prefix.prefix = cc_ratecard.destination ","DISTINCT cc_ratecard.id,cc_prefix.destination, cc_ratecard.dialprefix");
$rates_clauses = " cc_package_rate.package_id = $id";
$result_rates=$table_rates ->Get_list(DbConnect(), $rates_clauses);

?>

<SCRIPT LANGUAGE="javascript">
var win= null;
function addrate(selvalue){
	//test si win est encore ouvert et close ou refresh
    win=MM_openBrWindow('A2B_entity_def_ratecard.php?popup_select=1&package=<?php echo $id ?>','','scrollbars=yes,resizable=yes,width=700,height=500');
}
function delrate(){
	//test si val is not null & numeric
	if($('#rate').val()!=null){
		self.location.href= "A2B_package_manage_rates.php?id=<?php echo $id; ?>&delrate="+$('#rate').val();
	}
}


</SCRIPT>
<TABLE class="invoice_table" >
	<tr class="form_invoice_head">
	    <td widht="60%"><font color="#FFFFFF"><?php echo gettext("PACKAGE: "); ?></font><font color="#FFFFFF"><b><?php echo $result_pack[0]['label']; ?></b></font></td>
            <td width="40%"><font color="#FFFFFF"><?php echo gettext("DATE: "); ?> </font><font color="#EE6564"> <?php echo $result_pack[0]['creationdate']; ?></font></td>
	</tr>
	<tr>
            <td colspan="2">
			<?php echo gettext("PACKAGE TYPE"); ?>&nbsp;:&nbsp;<?php $pck_type = Constants::getPackagesTypeList(); echo $pck_type[$result_pack[0]['packagetype']][0]; ?>
		</td>
	</tr>
	<tr>
            <td colspan="2">
			<?php echo gettext("NUMBER"); ?>&nbsp;:&nbsp;<?php echo $result_pack[0]['freetimetocall']; ?>&nbsp;<?php $pck_type = Constants::getPackagesTypeList(); echo $pck_type[$result_pack[0]['packagetype']][0]; ?>&nbsp;<?php echo gettext('per') ?>
                        <?php if($result_pack[0]['billingtype']==1) echo gettext("month"); else echo gettext("week"); ?>
            </td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<br/>
			<table>
				<tr>
					<td align="center">
						<?php echo gettext("RATES ASSIGNED"); ?>
					</td>
				</tr>
				<tr>
					<td>
						<select id="rate" name="rate" size="5" style="width:250px;" class="form_input_select">
						    <?php foreach ($result_rates as $rate){ ?>
							<option value="<?php echo $rate['id'] ?>"  ><?php echo $rate['destination'] ;?>&nbsp;:&nbsp;<?php echo $rate['dialprefix']; ?>&nbsp;&nbsp;<?php echo "(id : ".$rate['id'].")";?> </option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="center">
						<a href="javascript:;" onClick="addrate()" > <img src="../Public/templates/default/images/add.png" title="Add Rate" alt="Add Rate" border="0"></a>
						<a href="javascript:;" onClick="delrate()" > <img src="../Public/templates/default/images/del.png" title="Del Rate" alt="Del Rate" border="0"></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>

</TABLE>



<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');
