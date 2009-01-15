<?php

getpost_ifset(array('name','path','creationdate'));

$HD_Form = new FormHandler("cc_backup","Backup");

$HD_Form -> FG_DEBUG = 0;

if ($form_action == 'add'){
        $backup_file = $path;

		if (substr($backup_file,-3)=='.gz'){
			// WE NEED TO GZIP
			$backup_file = substr($backup_file,0,-3);
			$do_gzip=1;
		}
        // Make the backup stuff here and redirect to success page
        //mysqldump -all --databases mya2billing -ua2billinguser -pa2billing > /tmp/test.sql
        //pg_dump -c -d -U a2billinguser -h localhost -f /tmp/test.sql mya2billing
		
        if (DB_TYPE != 'postgres'){
			$run_backup=MYSQLDUMP." -all --databases ".DBNAME." -u'".USER."' -p'".PASS."' > '{$backup_file}'";
        }else{
			$env_var="PGPASSWORD='".PASS."'";
			putenv($env_var);
			$run_backup=PG_DUMP." -c -d -U ".USER." -h ".HOST." -f '{$backup_file}' ".DBNAME;
        }

	if ($FG_DEBUG == 1 ) echo $run_backup."<br>";
	exec($run_backup);

	if ($do_gzip){
		// Compress file
		$run_gzip = GZIP_EXE." '$backup_file'";
		if ($FG_DEBUG == 1 ) echo $run_gzip."<br>";
		exec($run_gzip);
	}
}

$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;

$default_path = "value='".BACKUP_PATH."/db_". DBNAME . "-" . date('YmdHis') . ".sql.gz'";


$HD_Form -> AddEditElement (gettext("Name"),
	"name",
	"",
	"INPUT",
	"size=50 maxlength=50",
	"",
	gettext("Insert the name of backup"),
	"" , "", "", "", "", "", "", gettext("Name for the backup"));

$HD_Form -> AddEditElement (gettext("PATH"),
	"path",
	'',
	"INPUT",
	"size=100 maxlength=200 $default_path",
	"",
	gettext("Insert the patch for the backup file or leave the default path"),
	"" , "", "", "", "", "", "", gettext("Path for the backup file or leave the default path set in a2billing.conf") );


$HD_Form -> FieldEditElement ('name, path, creationdate');


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");



$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a ".$HD_Form->FG_INSTANCE_NAME." now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONTINUE button.");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = 'A2B_entity_restore.php?id';
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


