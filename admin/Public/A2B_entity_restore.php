<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/


include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_restore.inc");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_MAINTENANCE)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

if (!empty($form_action))
	check_demo_mode();

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

if ($id!="" || !is_null($id)) {
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);	
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

if ($form_action == "delete"){
	$instance_table = new Table($HD_Form -> FG_TABLE_NAME, null);
	$res_delete = $instance_table -> Delete_table ($HD_Form -> DBHandle, $HD_Form ->FG_EDITION_CLAUSE, null);
	if (!$res_delete){  
		echo "error deletion";
        }else{
		unlink($path);
        }
}
						
if ($form_action == "restore"){
	$instance_table_backup = new Table($HD_Form -> FG_TABLE_NAME,$HD_Form -> FG_QUERY_EDITION);
	$list = $instance_table_backup -> Get_list ($HD_Form -> DBHandle, $HD_Form -> FG_EDITION_CLAUSE, null , null , null , null , 1 , 0);
	$path = $list[0][1];
	
	if (substr($path,-3)=='.gz'){
			// WE NEED TO GZIP
			$run_gzip = GUNZIP_EXE." -c ".$path." | ";
	}
	
	if (DB_TYPE != 'postgres'){
		$run_restore = $run_gzip.MYSQL." -u ".USER." -p".PASS;
	}else{
		$env_var="PGPASSWORD='".PASS."'";
		putenv($env_var);
		$run_restore = $run_gzip.PSQL." -d ".DBNAME." -U ".USER." -h ".HOST;
	}

	if ($FG_DEBUG == 1) echo $run_restore."<br>";
	exec($run_restore);
}

if ($form_action == "download"){
	$instance_table_backup = new Table($HD_Form -> FG_TABLE_NAME,$HD_Form -> FG_QUERY_EDITION);
	$list = $instance_table_backup -> Get_list ($HD_Form -> DBHandle, $HD_Form -> FG_EDITION_CLAUSE, null , null , null , null , 1 , 0);
	$path = $list[0][1];
	$filename = basename($path);
	$len = filesize($path);
	header( "content-type: application/stream" );
	header( "content-length: " . $len );
	header( "content-disposition: attachment; filename=" . $filename );
	$fp=fopen( $path, "r" );
	fpassthru( $fp );
	exit;
}
													

if ($form_action == "upload"){

	$uploaddir = BACKUP_PATH.'/';
	$uploadfile = $uploaddir . basename($_FILES['databasebackup']['name']);

	if (move_uploaded_file($_FILES['databasebackup']['tmp_name'], $uploadfile)) {
		$instance_table_backup = new Table($HD_Form -> FG_TABLE_NAME, 'id, name, path, creationdate');
		$param_add_value = "'','Custom".date("Ymd-His")."','".$uploadfile."',now()";
		$result_query=$instance_table_backup -> Add_table ($HD_Form -> DBHandle, $param_add_value, null, null, null);
		if (isset($FG_GO_LINK_AFTER_UPLOAD)){
			Header("Location: $FG_GO_LINK_AFTER_UPLOAD");
			exit;
		}	
	} else {
		$error_upload=gettext("Error uploading file");
	}
}
																	

$list = $HD_Form -> perform_action($form_action);



// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_database_restore;



// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


$HD_Form -> create_form ($form_action, $list, $id=null) ;

if ($form_action == "list"){
?>
<script language="JavaScript" type="text/JavaScript">
<!--
function CheckForm() {
	var test = document.Dupload.databasebackup.value;
	if (test != "") document.Dupload.submit();
}

//-->
</script>
<table width="85%" border="0" align="center" cellpadding="0" cellspacing="0">
<form  name="Dupload" enctype="multipart/form-data" action="A2B_entity_restore.php" method="POST">
	<TR valign="middle">
		<TD align="center">
			<?php echo gettext("Upload a database backup")?>&nbsp;<input type="file" name="databasebackup" value="">
		<img src="<?php echo Images_Path;?>/clear.gif">
		<input type="hidden" name="MAX_FILE_SIZE" value="8000">
		<input type="hidden" name="form_action" value="upload">
		<input type="hidden" name="atmenu" value="upload">
		<input type="button" value="Upload" onclick="CheckForm()" class="form_input_button"> 
		</TD>
	</TR>
</form>
</table>
<?php
}

// #### FOOTER SECTION
$smarty->display('footer.tpl');

