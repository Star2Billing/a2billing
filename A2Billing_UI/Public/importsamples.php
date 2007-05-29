<?php

$cardSample_Simple = "1321321321;12312323325;1321321321;Churchill;12;Churchill;Winston;1
<br>1435345345;12312323444;1435345345;Raumon;12;Raumon;Carrette;1
<br>1321387788;12312323555;1321387788;VonDutch;12;VonDutch;Jaycon;1";

$cardSample_Complex = "
1321321321;12312323325;1321321321;Churchill;12;Churchill;Winston;1;2006-07-03 23:27:01;2006-07-03 23:27:01;2006-07-03 23:27:01;0;0;1;1;150 10th Street NW;Washington, DC;;USA;54000;0459230061;winston@churchill.com;0425700714;0;0;USD;2006-07-03 23:27:01;0;0;0;0;1;1;en;0;-1;0;0;200;0;2006-07-03 23:27:01;0;1;1;asd234asd3<br>
6434353300;643435330098;6434353300;Raumon;12;Raumon;Carrette;1;2006-07-03 23:27:01;2006-07-03 23:27:01;2006-07-03 23:27:01;0;0;1;1;150 14th NW;New York, DC;;USA;54000;0459230061;Raumon@hotmail.com;0425700999;0;0;USD;2006-07-03 23:27:01;0;0;0;0;1;1;en;0;-1;0;0;300;0;2006-07-03 23:27:01;0;1;1;asd98866<br>";

$ratecardSample_Simple = "1;US;0,70;0,50;2008-03-07 21:21:38
<br>34;Spain Fix;1,56;1,16;2008-03-07 21:21:38
<br>34650;Spain Mobile Movistar;1,56;1,18
<br>32;Belgium Fix;1,20;1,11
<br>32473;Belgium Mobile Proximus;1,70;1,44";

$ratecardSample_Complex = "33; France; 1,01; 30; 6; 1,23; 30; 6; 0,12; 0; 0,12; 1,34; 120; 20; 0;0;0;0;  0;0;0;0; 0; 0; 0;10079<br>32; Belgium; 1,30; 30; 6; 1,43; 30; 6; 0,12; 0; 0,12; 1,54; 180; 20; 0;0;0;0;  0;0;0;0; 0; 0; 0;10079
<br>34; Spain; 1,00; 30; 6; 1,10; 30; 6; 0,12; 0; 0,12; 1,14; 120; 0; 0;0;0;0;  0;0;0;0; 0; 0; 0;9079
<br>44; UK; 0,54; 30; 10; 0,78; 30; 6; 0,06; 0; 0,06; 0,85; 120; 0; 0;0;0;0;  0;0;0;0; 2005-02-10 21:23:55; 2005-04-15 10:00:00; 1;10079
<br>44; UK; 0,54; 30; 10; 0,89; 30; 6; 0,10; 0; 0,06; 0,94; 120; 0; 0;0;0;0;  0;0;0;0; 2005-04-15 10:00:00; 0; 1;2000";

$didSample_Simple = "2001;103<br>2002;104<br>2003;108<br>2004;105";

$didSample_Complex = "200;12;1;2006-07-17 19:48:07;2031-07-17 19:48:07;1
<br>300;12;1;2006-07-17 19:48:07;2031-07-17 19:48:07;1
<br>400;12;1;2006-07-17 19:48:07;2031-07-17 19:48:07;1
<br>500;12;1;2006-07-17 19:48:07;2031-07-17 19:48:07;1";

if (isset($_GET["sample"]))
{
    switch($_GET["sample"])
    {
        case "Card_Simple":
        echo $cardSample_Simple;
        break;

        case "Card_Complex":
        echo $cardSample_Complex;
        break;

        case "RateCard_Simple":
        echo $ratecardSample_Simple;
        break;

        case "RateCard_Complex":
        echo $ratecardSample_Complex;
        break;

        case "did_Simple":
        echo $didSample_Simple;
        break;
        
        case "did_Complex":
        echo $didSample_Complex;
        break;

        default:
        echo "No sample defined!";
        break;
        
    }
}
?>
