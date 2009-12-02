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



$phonebookSample_Simple = "003247354343
<br>003247354563
<br>003247354356";

$phonebookSample_Complex = "003247354343, Jean Pest, advertissing
<br>003247354563, Ale Beignard, Debt
<br>003247354356, James Bon, Debt";

echo "<font size=\"1\">";
if (isset($_GET["sample"]))
{
    switch($_GET["sample"])
    {
        case "Phonebook_Simple":
        echo $phonebookSample_Simple;
        break;

        case "Phonebook_Complex":
        echo $phonebookSample_Complex;
        break;

        default:
        echo "No sample defined!";
        break;       
    }
}
echo "</font>";


