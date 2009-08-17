<?php

$phonebookSample_Simple = "003247354343
<br>003247354563
<br>003247354356";

$phonebookSample_Complex = "003247354343;Jean Pest;advertissing
<br>003247354563;Ale Beignard;Debt
<br>003247354356;James Bon;Debt";

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
