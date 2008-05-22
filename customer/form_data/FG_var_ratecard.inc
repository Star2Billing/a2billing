<?php

getpost_ifset(array('destination', 'dialprefix', 'rateinitial','posted','tariffplan'));


//$HD_Form = new FormHandler("cc_ratecard","Ratecard");
$HD_Form = new FormHandler("cc_ratecard, cc_tariffgroup_plan, cc_tariffplan","Ratecard");


$HD_Form -> FG_DEBUG = 0;
// FAQ
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "destination";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_VIEW_TABLE_WITDH = "65%";
$HD_Form->FG_FILTER_SEARCH_SESSION_NAME = 'entity_ratecard_selection';


if (isset($posted) && strlen(trim($tariffplan)) > 0){
	$idtariff = explode('-:-',$tariffplan);
	$HD_Form->FG_TOP_FILTER_NAME = str_replace ("-:-","",$idtariff[1]);
	//echo "Debug hey ->".$HD_Form->FG_TOP_FILTER_NAME."<br>";
	$HD_Form->FG_TOP_FILTER_VALUE = $idtariff[0];
	$_SESSION["mytariff_id"] = $idtariff[0];
	//$HD_Form -> FG_TABLE_CLAUSE = " idtariffplan='$HD_Form->FG_TOP_FILTER_VALUE' ";
    $HD_Form -> FG_TABLE_CLAUSE .= "cc_tariffplan.id = cc_tariffgroup_plan.idtariffplan AND cc_tariffgroup_plan.idtariffgroup = '".$_SESSION["tariff"]."' AND  cc_tariffgroup_plan.idtariffplan=cc_ratecard.idtariffplan   AND cc_ratecard.idtariffplan='".$HD_Form->FG_TOP_FILTER_VALUE."' AND cc_tariffgroup_plan.idtariffgroup = '".$_SESSION["tariff"]."'";
	$_SESSION['def_ratecard'] = $HD_Form -> FG_TABLE_CLAUSE;	
}else{
	$HD_Form -> FG_TOP_FILTER_NAME = 0;
	$HD_Form -> FG_TABLE_CLAUSE = "cc_tariffplan.id = cc_tariffgroup_plan.idtariffplan AND cc_tariffgroup_plan.idtariffgroup = '".$_SESSION["tariff"]."' AND cc_tariffgroup_plan.idtariffplan=cc_ratecard.idtariffplan AND cc_ratecard.idtariffplan='".$_SESSION["mytariff_id"]."' AND cc_tariffgroup_plan.idtariffgroup = '".$_SESSION["tariff"]."'";
}



$HD_Form -> AddViewElement(gettext("DESTINATION"), "destination", "15%", "center", "sort", "");
$HD_Form -> AddViewElement("<acronym title=\"DIALING PREFIX\">".gettext("PREFIX")."</acronym>", "dialprefix", "5%", "center", "sort", "");
$HD_Form -> AddViewElement("<acronym title=\"SELLING RATE\">".gettext("SR")."</acronym>", "rateinitial", "4%", "center", "sort");


$HD_Form -> FieldViewElement ('destination, dialprefix, rateinitial',0);


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;



$HD_Form -> AddEditElement(gettext("DIALPREFIX"),
			   "dialprefix",
			   '$value',
			   "INPUT",
			   "size=50 maxlength=80",
			   "$dialprefix_regexpress",
			   gettext("Insert the dialing prefix, such as for UK '44' or for US '1' "),
			   "" , "", "",	"", "" , "", "", gettext("Define the destination prefix. ie, 44 for UK proper.")." ".gettext("You can use 'defaultprefix' to setup a rate by default for the destination for which you dont want to provide a specific rate.<br>").gettext("IF YOU ADD A RATE AND NOT AN EDIT => You can also define several in the same time and range, as below: ").gettext("<br>32484-32487 -> this would insert 32484, 32485, 32486, 32487 <br>32476,32477,32478 separate by comma"));

$HD_Form -> AddEditElement(gettext("DESTINATION"),
			   "destination",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=40",
			   "11",
			   gettext("Come on... Insert the destination name"),
			   "" , "", "",	"", "", "", "", gettext("Name for this call destination, for example : UK, Belgium Landline, Spain Mobile Telefonica, ..."));


$HD_Form -> AddEditElement(gettext("SELLING RATE"),
			   "rateinitial",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=20",
			   "12",
			   gettext("Insert the initial rate"),
			   "" , "", "",	"", "" , "", "", gettext("Cost per minute for which you want to charge your customer. For example '0.02'"), null, null, gettext("Define the selling cost for customers..."));



$HD_Form -> FG_SPLITABLE_FIELD = 'dialprefix';


?>
