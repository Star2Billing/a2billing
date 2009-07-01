<?php

getpost_ifset(array('destination', 'dialprefix', 'rateinitial','posted','tariffplan'));


$HD_Form = new FormHandler("cc_ratecard, cc_tariffgroup_plan, cc_tariffplan, cc_prefix","Ratecard");


$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "destination";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_VIEW_TABLE_WITDH = "65%";
$HD_Form->FG_FILTER_SEARCH_SESSION_NAME = 'entity_ratecard_selection';


if (isset($posted) && strlen(trim($tariffplan)) > 0) {
	$idtariff = explode('-:-',$tariffplan);
	$HD_Form->FG_TOP_FILTER_NAME = str_replace ("-:-","",$idtariff[1]);
	$HD_Form->FG_TOP_FILTER_VALUE = $idtariff[0];
	$_SESSION["mytariff_id"] = $idtariff[0];
	$HD_Form -> FG_TABLE_CLAUSE .= "cc_tariffplan.id = cc_tariffgroup_plan.idtariffplan AND cc_tariffgroup_plan.idtariffgroup = '".$_SESSION["tariff"]."' AND  cc_tariffgroup_plan.idtariffplan=cc_ratecard.idtariffplan AND cc_ratecard.idtariffplan='".$HD_Form->FG_TOP_FILTER_VALUE."' AND cc_tariffgroup_plan.idtariffgroup = '".$_SESSION["tariff"]."' AND cc_prefix.prefix = cc_ratecard.destination";
	$_SESSION['def_ratecard'] = $HD_Form -> FG_TABLE_CLAUSE;	
} else {
	$HD_Form -> FG_TOP_FILTER_NAME = 0;
	$HD_Form -> FG_TABLE_CLAUSE = "cc_tariffplan.id = cc_tariffgroup_plan.idtariffplan AND cc_tariffgroup_plan.idtariffgroup = '".$_SESSION["tariff"]."' AND cc_tariffgroup_plan.idtariffplan=cc_ratecard.idtariffplan AND cc_tariffgroup_plan.idtariffgroup = '".$_SESSION["tariff"]."' AND cc_prefix.prefix = cc_ratecard.destination";
}


$HD_Form -> AddViewElement(gettext("DESTINATION"), "destination", "17%", "center", "sort", "40");
$HD_Form -> AddViewElement("<acronym title=\"DIALING PREFIX\">".gettext("PREFIX")."</acronym>", "dialprefix", "5%", "center", "sort", "");
$HD_Form -> AddViewElement("<acronym title=\"SELLING RATE\">".gettext("SR")."</acronym>", "rateinitial", "4%", "center", "sort");


$HD_Form -> FieldViewElement ('cc_prefix.destination, dialprefix, rateinitial',0);


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO RATECARD CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_SPLITABLE_FIELD = 'dialprefix';



