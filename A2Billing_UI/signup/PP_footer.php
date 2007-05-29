<?php
	if (isset($displayfooter) && $displayfooter==0){ echo '</body></html>'; exit();}

    include (dirname(__FILE__)."/../lib/company_info.php");
	if ($DBHandle){ DbDisconnect($DBHandle);}
?>


<br></br>
<div id="kiblue"><div class="w1"><?php  echo COPYRIGHT; ?></div></div>
<br>
</body>
</html>
