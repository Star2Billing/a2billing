<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <TITLE>ARI</TITLE>
    <link rel="stylesheet" href="theme/main.css" type="text/css">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  </head>
  <body>
  <div id="page">
  <div class="minwidth">
  <div class="container">
    <h2 class="ariBlockHide">Header And Logo</h2>
    <div id="ariHeader">
      <div class="spacer"></div>
      <span id="left">
        <a href="<?=$_SERVER['PHP_SELF']?>" alt="ARI"><img src="theme/logo.gif" height=72 alt="" /></a>
      </span>
      <span id="right"></span>
      <div class="spacer"></div>
    </div>
    <div id="topnav">
      <div class="spacer"></div>
      <span class="left">
        <small>&nbsp;&nbsp;&nbsp;&middot;&nbsp;&middot;&nbsp;&middot;&nbsp;&middot;&nbsp;&middot;&nbsp;&middot;&nbsp;&middot;&nbsp;&middot;&nbsp;</small>
      </span>
      <div class="spacer"></div>
    </div> 
    <div id="headerspacer"><img src="theme/spacer.gif" alt=""></div> 
    <div id="main">
    <div class="minwidth">
    <div class="container">
      <div class="spacer"></div>
      <span class="left">
        <div id="menu">
          <div><img height=4 src="theme/spacer.gif" alt=""></div> 
          <div class="nav">
            <?php if ($nav_menu != '') { ?>
              <b class='nav_b1'></b><b class='nav_b2'></b><b class='nav_b3'></b><b class='nav_b4'></b>
              <div id='nav_menu'>
                  <?php print($nav_menu) ?>
              </div>
              <b class='nav_b4'></b><b class='nav_b3'></b><b class='nav_b2'></b><b class='nav_b1'></b>
            <?php } ?>
          </div>
          <div><img height=14 src="theme/spacer.gif" alt=""></div> 
          <?php if ($subnav_menu != '') { ?>
            <div class="subnav">
              <div class="subnav_title"><?echo _("Folders")?>:</div>
              <b class='subnav_b1'></b><b class='subnav_b2'></b><b class='subnav_b3'></b><b class='subnav_b4'></b>
              <div id='subnav_menu'>
                <?php print($subnav_menu) ?>
              </div>
              <b class='subnav_b4'></b><b class='subnav_b3'></b><b class='subnav_b2'></b><b class='subnav_b1'></b>
            </div>
          <?php } ?>
        </div>
      </span>
      <span class="right">
        <div id="center">
          <?php if ($login != "") { ?>
            <?php print($login) ?>
          <?php } ?>
          <div id="content">
            <!-- begin main content -->
              <?php print($content) ?>
            <!-- end main content -->
          </div>
        </div>
      </span>
      <div class="spacer"></div>
    </div>
    </div>
    </div>
    <!--begin footer-->
    <div id="ariFooter">
      <small>
        &nbsp;&nbsp;<?echo _("Version")?> <?php print($ari_version) ?><br>
        &middot;&nbsp;<a href="http<? print(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=''?'s':''); ?>://www.littlejohnconsulting.com">Littlejohn Consulting</a>
      </small>
    </div>
    <!-- end footer -->
  </div>
  </div>
  </div>
  </body>
</html>

