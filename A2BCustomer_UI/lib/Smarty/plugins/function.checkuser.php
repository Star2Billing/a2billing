<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {checkuser} function plugin
 *
 * Type:     function<br>
 * Name:     Vijay Nair<br>
 * Date:     March 29, 2006<br>
 * Purpose:  Take languave specific texts from database to display
 * @link     To be attached with osdate package and topied to Smarty/plugins directory
 * @author   Vijay Nair <vijay@nairvijay.com>
 * @version  1.0
 * @param    userid, checkfor
 * @return   string
 */

function smarty_function_checkuser($params, &$smarty )
{  	global $db, $config, $lang;
   	$returnme = '';
	$checkfor = $params['checkfor'];
	$userid = $params['userid'];
	$username = $params['username'];
   	if ($checkfor == 'online') {
		/* Check if the user is online */
   		$sql = 'select count(*) from ! where userid = ?';
   		$online=$db->getOne($sql, array(ONLINE_USERS_TABLE, $userid));
   		if (isset($online) && $online > 0) {
   			$returnme='<b><font color="'.$lang['useronlinecolor']['online_now'].'">'.$lang['useronlinetext']['online_now'].'</font></b>';
   		} else {
			$lastvisit = $db->getOne('select lastvisit from ! where id = ?', array(USER_TABLE, $userid) );
			if (!isset($lastvisit) ) $lastvisit = time() - 3456000; /* 4-0 days back */
			$time_now = time();
			if ($lastvisit > ($time_now-86400) ) {
				/* Active in last 24 hours */
	   			$returnme='<b><font color="'.$lang['useronlinecolor']['active_24hours'].'">'.$lang['useronlinetext']['active_24hours'].'</font></b>';
			} elseif ($lastvisit > ($time_now-259200) ) {
				/* Active in last 3 days */
	   			$returnme='<b><font color="'.$lang['useronlinecolor']['active_3days'].'">'.$lang['useronlinetext']['active_3days'].'</font></b>';
			} elseif ($lastvisit > ($time_now-604800) ) {
				/* Active in last 7 days */
	   			$returnme='<b><font color="'.$lang['useronlinecolor']['active_1week'].'">'.$lang['useronlinetext']['active_1week'].'</font></b>';
			} elseif ($lastvisit > ($time_now-2592000) ) {
				/* Active in last 30 days */
	   			$returnme='<b><font color="'.$lang['useronlinecolor']['active_1month'].'">'.$lang['useronlinetext']['active_1month'].'</font></b>';
			} else {
	   			$returnme='<b><font color="'.$lang['useronlinecolor']['notactive'].'">'.$lang['useronlinetext']['notactive'].'</font></b>';
			}
   		}
   	} elseif ($checkfor == 'buddy' or $checkfor == 'ban' or $checkfor == 'hot') {
		/* Check if the user is in the buddy list */
		if ($checkfor == 'buddy') {$act = 'F'; $tit="User is in Buddy List";}
		elseif ($checkfor == 'ban') {$act = 'B'; $tit="User is in Banned Liat";}
		else {$act = 'H'; $tit = "User is in Hot List";}
   		$sql = 'select count(*) from ! where username = ? and ref_username = ? and act=?';
		$isthere = $db->getOne($sql, array(BUDDY_BAN_TABLE, $_SESSION['UserName'], $username ,$act) );
		if (isset($isthere) && $isthere > 0) {
			if ($act == 'H') {
				$returnme = '<img src="images/hot_list.gif" height="12" width="12" alt="" align="baseline" title="'.$tit.'" />';
			} elseif ($act == 'F') {
				$returnme = '<img src="images/buddy_list.gif" height="12" width="12" alt="" align="baseline" title="'.$tit.'" />';
			} else {
				$returnme = '<img src="images/cross.jpg" height="12" width="12" alt="" align="baseline" title="'.$tit.'" />';
			}
		}
   	} elseif ($checkfor == 'message') {
   		/* check if you have received a message from this user and mail is still in the mailbox */
		$sql =  "select count(*) from ! where senderid = ? and recipientid = ?";
		$mail = $db->getOne($sql,array(MAILBOX_TABLE, $userid, $_SESSION['UserId']) );
		if (isset($mail) && $mail > 0) {
			$returnme = '<img src="images/unread.jpg" height="12" width="12" alt="" align="baseline" title="User has sent message to you" />';
		}
	} elseif ($checkfor == 'featured') {
		/* Check if this user is in featured list */
		$sql = 'select 1 from ! where userid = ? and exposures < req_exposures and ? between start_date and end_date';
		$feat = $db->getOne($sql, array(FEATURED_PROFILES_TABLE, $userid, time()));
		if (isset($feat) && $feat > 0) {
			$returnme = '<img src="images/featured.gif" height="12" width="12" alt="" align="baseline" title="User is in Featured List" />';
		}
	}
	return $returnme;
}

/* vim: set expandtab: */

?>
