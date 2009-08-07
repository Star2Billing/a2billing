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



