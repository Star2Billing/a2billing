<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_MAINTENANCE)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

/***********************************************************************************/


// #### HEADER SECTION
$smarty->display('main.tpl');

?>
<br>

<center>
<style type="text/css">
.phpinfodisplay table {border-collapse: collapse; font-size: 12px;}
.phpinfodisplay td,.phpinfodisplay  th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline; font-size: 12px;}
</style>


<?php
ob_start();
phpinfo();

preg_match ('%<style type="text/css">(.*?)</style>.*?(<body>.*</body>)%s', ob_get_clean(), $matches);

# $matches [1]; # Style information
# $matches [2]; # Body information

echo "<div class='phpinfodisplay'><style type='text/css'>\n",
    join( "\n",
        array_map(
            create_function(
                '$i',
                'return ".phpinfodisplay " . preg_replace( "/,/", ",.phpinfodisplay ", $i );'
                ),
            preg_split( '/\n/', $matches[1] )
            )
        ),
    "</style>\n",
    $matches[2],
    "\n</div>\n";
?>

</center>


<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');
?>