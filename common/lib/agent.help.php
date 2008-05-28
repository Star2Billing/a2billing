<?php

function help_wiki_link($wiki_article_name)
{
	return gettext("For further information please consult").' <a target="_blank" href="http://trac.asterisk2billing.org/cgi-bin/trac.cgi/wiki/1-4-'.$wiki_article_name.'">'.gettext("the online documention").'</a>.<br/>';
}


if (SHOW_HELP){

$CC_help_agent ='
<div class="toggle_show2hide">
<a href="#" target="_self" class="toggle_menu"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kdmconfig.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("Agent list who have access to the Agent interface.").'
<br/>'.help_wiki_link('ShowAgent').'<br/>
</div></div></div>
</div></div>';

} //ENDIF SHOW_HELP


