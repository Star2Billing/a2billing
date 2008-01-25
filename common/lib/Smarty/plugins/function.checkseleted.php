<?php

function smarty_function_checkseleted($params, &$smarty)
{
	
	//if($params["file"]=="" && $_REQUEST["sitestyle"]=="")	
	
	if($params["file"]=="" && $_POST["cssname"]=="")
	{
		return "selected";
	}
	else
	{
		if($_POST["cssname"]==$params["file"])
		{
			return "selected";
		}
		else
		{
			return "";
		}
	}
}
?>
