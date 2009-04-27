<?php

$regular = array ();

// 0 
$regular[] = array (
	"^.{3}",
	"(AT LEAST 3 CARACTERS)"
);

// 1
$regular[] = array (
	"^[0-9A-Za-z_]([-.]?[0-9A-Za-z_])*@[0-9A-Za-z][-.0-9A-Za-z]*\\.[A-Za-z]{2,4}[.]?$",
	"(MATCH WITH THE EMAIL STRUCTURE. ie : name@domain.ext)"
);

// 2
$regular[] = array (
	".{5}$",
	"(AT LEAST 5 SUCCESSIVE CHARACTERS APPEAR AT THE END OF THIS STRING)"
);

// 3
$regular[] = array (
	".{4}",
	"(AT LEAST 4 CHARACTERS)"
);

// 4
$regular[] = array (
	"^[0-9]+$",
	"(NUMBER FORMAT)"
);

// 5
$regular[] = array (
	"^([0-9]{4})-([0-9]{2})-([0-9]{2})$",
	"(YYYY-MM-DD)"
);

// 6
$regular[] = array (
	"^[0-9]{8,}$",
	"(only number with more that 8 digits)"
);

// 7
$regular[] = array (
	"^[0-9][ .0-9\/\-]{6,}[0-9]$",
	"(phone number with more that 8 digits with . or - or space)"
);

// 8 
$regular[] = array (
	".{5}",
	"network adress format"
);

// 9
$regular[] = array (
	"^.{1}",
	"(AT LEAST 1 CARACTERS)"
);

// 10
$regular[] = array (
	"^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$",
	"(YYYY-MM-DD HH:MM:SS)"
);

// 11 
$regular[] = array (
	"^.{2}",
	"(AT LEAST 2 CARACTERS)"
);

// 12
$regular[] = array (
	"^(-){0,1}[0-9]+(\.){0,1}[0-9]*$",
	"(NUMBER FORMAT WITH/WITHOUT DECIMAL, use '.' for decimal)"
);

// 13  - RATECARD
$regular[] = array (
	"^(defaultprefix|[0-9]+)$",
	"(NUMBER FORMAT OR 'defaultprefix')"
);

// 14  - DNID PREFIX FOR RATECARD
$regular[] = array (
	"^(all|[0-9]+)$",
	"(NUMBER FORMAT OR 'all')"
);

// 15 - RATECARD TIME
$regular[] = array (
	"^([0-9]{2}):([0-9]{2})$",
	"(HH:MM)"
);



