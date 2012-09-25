<div class="wrap" style="max-width:950px !important;">
    <h2>A2Billing Settings</h2>

    <div id="poststuff" style="margin-top:10px;">

     <div id="sideblock" style="float:right;width:220px;margin-left:10px;">
         <div id="dbx-content" style="text-decoration:none;">
             <p align="center">
             <img src="<?php echo $imgpath ?>/a2b-logo.png"></p>
             <br/><br/><br/>

             <img src="<?php echo $imgpath ?>/house.png"><a style="text-decoration:none;" href="http://www.asterisk2billing.org/"> A2Billing Home</a><br /><br />
             <img src="<?php echo $imgpath ?>/help.png"><a style="text-decoration:none;" href="http://forum.asterisk2billing.org/index.php"> Plugins Forum</a><br /><br />
             <img src="<?php echo $imgpath ?>/star.png"><a style="text-decoration:none;" href="http://wordpress.org/extend/plugins/a2billing/"> Rate A2Billing</a><br /><br />
             <br/>

         </div>
     </div>

     <div id="mainblock" style="width:710px">

        <div class="dbx-content">
             <form name="A2Billing" action="<?php echo $action_url ?>" method="post">
                    <input type="hidden" name="submitted" value="1" />
                    <h3><?php _e('Usage')?></h3>
                    <p><?php _e('You can display your rates by using the [a2billing_rates callplan_id=\'1\'] code in your posts.')?><br /><br />

                        tag [a2billing_rates callplan_id='1'] : To display rates for the callplan with ID = 1<br/>
                    </p>
                    <br/>

                    <h3><?php _e('Options')?></h3>

                    <br/>
                    WebService URL Display Rates : <br/><br/>
                    <input type="text" size="80" name="url_a2billing_rates" value="<?php echo $url_a2billing_rates; ?>"/><br/>

                    <br/>
                    API Private Key : <br/><br/>
                    <input type="text" size="30" name="api_private_key" value="<?php echo $api_private_key; ?>"/><br/>

                    <div class="submit"><input type="submit" name="Submit" value="<?php _e('Update')?>" /></div>
            </form>
        </div>

        <br/><br/><h3>&nbsp;</h3>
     </div>

    </div>

<h5>WordPress plugin by <a href="http://www.a2billing.net/">Belaid Arezqui</a> : areski@gmail.com </h5>
</div>
