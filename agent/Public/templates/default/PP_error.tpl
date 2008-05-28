{include file="header.tpl"}

<table border=0 width="1000">
<tr>
	<td width="175">	
	<div>
		{include file="leftmenu.tpl"}
	</div>
	</td>
	<td width="825">	
		<div>

            <table width="460" border="2" align="center" cellpadding="1" cellspacing="2" bordercolor="#eeeeff" bgcolor="#FFFFFF">
			  <tr bgcolor=#4e81c4>

					<td>
						<div align="center"><b><font color="white" size=5>{php} echo gettext("Error Page");{/php}</font></b></div>
					</td>
			  </tr>
              <tr>
                <td align="center" colspan=2>
                    <table width="100%" border="0" cellpadding="5" cellspacing="5">
                      <tr>
                        <td align="center"><br/>
						<img src="./Css/kicons/system-config-rootpassword.png">
						<br/>

						 <b><font color=#3050c2 size=4>{$error}</font></b><br/><br/><br/></td>
                      </tr>
                    </table>
				</td>
              </tr>
            </table>

</div>
</td>
</tr>
</table>

