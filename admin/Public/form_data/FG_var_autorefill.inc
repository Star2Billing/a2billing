<?php
getpost_ifset(array('id'));


$HD_Form = new FormHandler("cc_autorefill_report","AutoRefill");

$HD_Form -> FG_DEBUG = 0;

$HD_Form -> FG_VIEW_TABLE_WITDH = '70%';

$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form -> AddViewElement(gettext("DATE RUN"), "daterun", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("TOTALCARD PERFORM"), "totalcardperform", "20", "center", "sort");
$HD_Form -> AddViewElement(gettext("TOTALCREDIT"), "totalcredit", "20%", "center", "sort");;


// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('daterun, totalcardperform, totalcredit');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");  
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_ADDITION = false;
