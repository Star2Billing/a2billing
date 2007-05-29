<?php

function smarty_function_checkseleted($params, &$smarty)
{
	getpost_ifset(array('cssname'));		
	if($params["file"]=="" && $_SESSION["stylefile"]=="")
	{
		return "selected";
	}
	else
	{
		if($_SESSION["stylefile"]==$params["file"])
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
