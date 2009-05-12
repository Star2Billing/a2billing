<?php

getpost_ifset(array('id', 'payment_method', 'payment_filename'));

$HD_Form = new FormHandler("cc_payment_methods", "payment method");

$HD_Form->FG_DEBUG = 0;
$HD_Form->FG_TABLE_DEFAULT_ORDER = "payment_method";
$HD_Form->FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form->AddViewElement(gettext("Payment Method"), "payment_method", "25%", "center", "sort");
$HD_Form->AddViewElement(gettext("File Name"), "payment_filename", "20%", "center", "sort");

$HD_Form->FieldViewElement('payment_method, payment_filename');

$HD_Form->FG_OTHER_BUTTON1_LINK = "A2B_entity_payment_settings.php?id=";
$HD_Form->FG_OTHER_BUTTON1_IMG = Images_Path . "/icon-viewdetails.gif";

$HD_Form->CV_NO_FIELDS = gettext("THERE ARE NO " . strtoupper($HD_Form->FG_INSTANCE_NAME) . " CREATED!");
$HD_Form->CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form->CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form->CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form->FG_EDITION = false;
$HD_Form->FG_DELETION = false;
$HD_Form->FG_ADDITION = false;
$HD_Form->FG_OTHER_BUTTON1 = true;