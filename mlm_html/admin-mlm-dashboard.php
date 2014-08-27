<?php

function mlmShowDashboard() {
global $wp_rewrite; 

    $UMP_Instance = new UMP();
    $latest_ump_ver = $UMP_Instance->Plugin_Latest_Version();
    if (!$latest_ump_ver) {
        $latest_ump_ver = $UMP_Instance->Version;
    }
    $reversion = preg_split('/[ \.-]/', $UMP_Instance->Version);
    $ump_version = ump_arrval($reversion, 0) . '.' . ump_arrval($reversion, 1);
    $ump_build = ump_arrval($reversion, 2);
    $ump_stage = ump_arrval($reversion, 3);
    ?>

    <script>
        jQuery(document).ready(function() {
            jQuery('a:contains(Upgrade)').click(function() {
                var validity = '<?php echo is_update() ?>';
                if (validity != 1) {
                    var res = confirm('<?php _e('Your Licence Key has expired. Though you can continue to use the plugin as is, you will not be able to get updates. In order to get updates for the next 1 year you would need to renew your license.\n\n To renew your license key for another 1 year\n Click OK and you are redirected for complete renewal. ', 'unilevel-mlm-pro') ?>');
                    if (res == true) {
                        window.open('<?php echo WP_BINARY_MLM_ULR?>/my-account/', '_blank');
                    }
                    else {
                        return false;
                    }
                }

            });
        });

        function download_UMP() {
            var validity = '<?php echo is_update() ?>';
            if (validity == 1) {
                window.location = "<?php
    $UMP_Instance = new UMP();
    echo $UMP_Instance->Plugin_Download_Url()
    ?>";
            }
            else {
                var res = confirm('<?php _e('You will now be redirected to your My Account page at'.WP_BINARY_MLM_ULR, 'unilevel-mlm-pro') ?>');
                if (res == true) {
                    window.open('<?php echo WP_BINARY_MLM_ULR?>/my-account/', '_blank');
                }
            }
        }
        
        function download_PMP() {
            var validity = '<?php echo has_buy() ?>'; 
            if (validity == 1) {
                window.location = "<?php
    $UMP_Instance = new UMP();
    echo $UMP_Instance->Plugin_mlm_Mass_Pay_Download_Url()
    ?>";
            }
            else {
                alert('<?php _e('Please validate your email address first than you click download', 'unilevel-mlm-pro') ?>');
                return false;
            }
        }
        function purchase_PMP() { 
             var res = confirm('<?php _e('You you are redirecting at '.WP_BINARY_MLM_ULR, 'unilevel-mlm-pro') ?>');
                if (res == true) {
                    window.open('<?php echo WP_BINARY_MLM_ULR?>/product/paypal-mass-payments//', '_blank');
                }
            }
        
    </script>

    <div class='wrap'>
        <div id="icon-users" class="icon32"></div><h1><?php _e('Unilevel MLM Pro Dashboard', 'unilevel-mlm-pro'); ?></h1>
        <div id="dashboard-widgets-wrap">
            <div id="dashboard-widgets" class="metabox-holder">

                <!-- BEGIN LEFT POSTBOX CONTAINER -->
                <div class='postbox-container' style='width:49%;margin-right:1%'>			
                    <!-- BEGIN NEW POSTBOX -->
                    <div id="wl_dashboard_right_now" class="postbox">
                        <h3><?php _e('NAVIGATION MENU', 'unilevel-mlm-pro'); ?></h3>
                        <!-- begin inside content -->
                        <div class="inside">
                            <p>
                                <strong><?php $UMP_Instance->GetMenu('Settings', 'admin-settings', true); ?></strong> - <?php _e('Configure the settings for your Network.', 'unilevel-mlm-pro'); ?><br /><br />
                                <strong><?php $UMP_Instance->GetMenu('Run Payouts', 'mlm-payout', true); ?></strong> - <?php _e('Used to manually run the Payout routine. Ideally payouts should be managed via Cron Jobs.', 'unilevel-mlm-pro'); ?><br /><br />
                                <strong><?php $UMP_Instance->GetMenu('User Report', 'mlm-user-account', true); ?></strong> - <?php _e('Lookup member info by Username or Email', 'unilevel-mlm-pro'); ?><br /><br />
                                <strong><?php $UMP_Instance->GetMenu('Withdrawals', 'admin-mlm-pending-withdrawal', true); ?></strong> - <?php _e('Manage the Pending User Withdrawals from this page.', 'unilevel-mlm-pro'); ?><br /><br />
				<strong><?php $UMP_Instance->GetMenu('Payment Settings', 'admin-mlm-payment-settings', true); ?></strong> - <?php _e('Configure settings for your Payment Gateway.', 'unilevel-mlm-pro'); ?><br /><br />
				<strong><?php $UMP_Instance->GetMenu('Reports', 'admin-reports', true); ?></strong> - <?php _e('Access various Reports to get useful information about your network.', 'unilevel-mlm-pro'); ?><br /><br />
                            </p>
                        </div>
                    </div><!-- END THIS POSTBOX -->
                    <div class="postbox">
                        <h3><?php _e('UPGRADE UNILEVEL MLM PRO', 'unilevel-mlm-pro'); ?></h3>
                        <!-- begin inside content -->
                        <div class="inside">
                            <?php if ($UMP_Instance->Plugin_Is_Latest()): ?>
                                <p>
                                    <!--<a style="float:right" href="?<?php echo $_SERVER['QUERY_STRING']; ?>&checkversion=1"><?php _e('Check for Updates', 'unilevel-mlm-pro'); ?></a>-->
                                    <span style="color:green"><?php printf(__('You have the latest version of <strong>Unilevel MLM Pro</strong> (v%1$s)', 'unilevel-mlm-pro'), $ump_version); ?></span>
                                </p>
                                <p style="text-align:left; ">
                                    <?php printf(__('<input type="button" id="download" class="button-primary" value="Download Unilevel MLM Pro" onclick="download_UMP()" />', 'unilevel-mlm-pro'), $UMP_Instance->Plugin_Download_Url()); ?></p>
                                <p style="text-align:left; ">
                                    <?php echo lic_till_valid() ?> .
                                </p><p></p>
                            <?php else: ?>
                                <p><?php printf(__('You are currently running on <strong>Unilevel MLM Pro</strong> version %1$s', 'unilevel-mlm-pro'), $ump_version); ?>
                                    <br />
                                    <span style="color:red"><?php printf(__('* The most current version is version %1$s', 'unilevel-mlm-pro'), $latest_ump_ver); ?></span></p>
                                <p style="text-align:left; ">
                                    <?php printf(__('<a href="%2$s" class="button-primary" id="upgrade" >Upgrade</a> &nbsp;&nbsp; <input type="button" id="download" class="button-primary" value="Download Unilevel MLM Pro" onclick="download_UMP()" />', 'unilevel-mlm-pro'), $UMP_Instance->Plugin_Download_Url(), $UMP_Instance->Plugin_Update_Url()); ?></p>
                                <p style="text-align:left; ">
                                    <?php echo lic_till_valid() ?> .
                                </p><p></p>
                            <?php endif; ?>

                        </div>
                        <!-- end inside -->
                    </div>
                    <!-- END THIS POSTBOX -->
<?php
  if(!empty($_POST['wpbinary_user_email'])){
      update_option('wpbinary_user_email',$_POST['wpbinary_user_email']);
      has_buy();
      echo "<script>window.location=''</script>";
  }
?>
		
                        <!-- BEGIN NEW POSTBOX -->
                        <div class="postbox" >
                            <h3><?php _e('MLM PAYPAL MASS PAY', 'unilevel-mlm-pro'); ?></h3>
                            <!-- begin inside content -->
                            <div class="inside">
                                <p style="text-align:left; ">
                                    <?php if(!has_buy()){?>
                                    <span style='color:red'>Please purchase MLM Paypal Mass Pay than  Validate your email address .</span><br/>
                                    <?php _e('<input type="button" id="purchase" class="button-primary" value="Purchase MLM Paypal Mass Pay" onclick="purchase_PMP()" />', 'unilevel-mlm-pro');?><br/><br/>
                                    
                                    <form method="post" >
                                    <p class="submit">
                                        <span style='color:red'><?php _e('Please validate your email address first than you click download.','unilevel-mlm-pro');?></span><br/>
                                        <?php _e("Please enter same email Address that which given time of purchase product of <br/><b>MLM Paypal Mass Pay</b>", 'unilevel-mlm-pro'); ?><br />
                                        <input type="email" name="wpbinary_user_email" value=""  placeholder="Enter Email Address" required/>
                                        <input type="submit" class="button-secondary" value="Submit" name="Submit" />
                                    </p>
                                </form><br />
                                    <?php } 
                                 else { 
                                 echo "<span style='color:green'> validate your email address sucessfully.</span><br/>";
_e('<input type="button" id="download" class="button-primary" value="Download MLM Paypal Mass Pay" onclick="download_PMP()" />', 'unilevel-mlm-pro');
                                 } ?>
                                <br />
                                    <?php 
                                    
                                    
                                    ?>
                                </p>
                            </div>
                            <!-- end inside -->
                        </div>
                        <!-- END THIS POSTBOX -->

                        <?php if (!$UMP_Instance->isURLExempted(strtolower(get_bloginfo('url')))): ?>
                        <!-- BEGIN NEW POSTBOX -->
                        <div class="postbox" style="display:none">
                            <h3><?php _e('Deactivate Unilevel MLM Pro', 'unilevel-mlm-pro'); ?></h3>
                            <!-- begin inside content -->
                            <div class="inside">
                                <form method="post" onsubmit="return confirm('<?php _e('Are you sure that you want to deactivate the license of this plugin for this site?', 'unilevel-mlm-pro'); ?>')">
                                    <p class="submit"><?php _e("If you're migrating your site to a new server, or just need to cancel your license for this site, click the button below to deactivate the license of this plugin for this site.", 'unilevel-mlm-pro'); ?><br /><br />
                                        <input type="hidden" name="wordpress_wishlist_deactivate" value="<?php echo $UMP_Instance->ProductSKU; ?>" />
                                        <input type="submit" class="button-secondary" value="Deactivate License For This Site" name="Submit" />
                                    </p>
                                </form>
                            </div>
                            <!-- end inside -->
                        </div>
                        <!-- END THIS POSTBOX -->

                    <?php endif; ?>
                    <p>
                        <small><strong>Unilevel MLM Pro</strong> v<?php echo $ump_version; ?> |  Build  <?php echo $ump_build; ?> <?php echo $ump_stage; ?> | WordPress <?php echo get_bloginfo('version'); ?> | PHP <?php echo phpversion(); ?> on <?php echo php_sapi_name(); ?></small>
                    </p>
                </div>
                <!-- END LEFT POSTBOX CONTAINER -->
<style type="text/css">
			#revalidate{background: #ffe9ad url(../wp-content/plugins/unilevel-mlm-pro/images/info1.png) no-repeat 0 31px;}
		</style>
                <!-- BEGIN RIGHT POSTBOX CONTAINER -->
                <div class="postbox-container" style="width:49%;">

                    <!-- BEGIN SUPPORT POSTBOX -->
                    <div class="postbox">
                        <h3><?php _e('LICENSE SETTINGS', 'unilevel-mlm-pro'); ?></h3>
                        <!-- begin inside content -->
                        <div class="inside umpsuppport-widget">
                            <?php mlm_licenese_settings_new(); ?>
                        </div>
                        <!-- end inside -->
                    </div>
                    <!-- END SUPPORT POSTBOX -->

                    <!-- BEGIN SUPPORT POSTBOX -->
                    <div class="postbox">
                        <h3><?php _e('SUPPORT', 'unilevel-mlm-pro'); ?></h3>
                        <!-- begin inside content -->
                        <div class="inside umpsuppport-widget">

                            <?php
                            //links, I have small screen so I want a line to be shorter
							$faq_lnk = WP_BINARY_MLM_ULR."/faqs/";
                            $priority_support = WP_BINARY_MLM_ULR."/product/priority-support/";
                            $blog = WP_BINARY_MLM_ULR."/blog/";
                            ?>
                            
							<table class="widefat">
                                <tr class="first">
                                    <td>
                                        <strong><a href="<?php echo $faq_lnk; ?>" target="_blank"><?php _e('FAQs', 'unilevel-mlm-pro'); ?></a></strong> - <?php _e('Access the FAQs on our website to get answers to your Frequently Asked Questions.', 'unilevel-mlm-pro'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><a href="<?php echo $blog; ?>" target="_blank"><?php _e('Blog', 'unilevel-mlm-pro'); ?></a></strong> - <?php _e('Access the Blog on our website.', 'unilevel-mlm-pro'); ?>
                                    </td>
                                </tr>									
                                <tr>
                                    <td>
                                        <strong><u>Customer / Technical Support:</u></strong>
                                    </td>	
                                </tr>
                                <tr>
                                    <td>
                                        <strong><a href="<?php echo $priority_support; ?>" target="_blank"><?php _e('Priority Support', 'unilevel-mlm-pro'); ?></a></strong> - <?php _e('For issues of urgent nature or for issues outside the scope of our standard support.', 'unilevel-mlm-pro'); ?>
                                    </td>	
                                </tr>									
                                	
				<tr>
                                    <td>
                                        <strong><?php _e('Regular Support', 'unilevel-mlm-pro'); ?></strong> - <?php _e('Send us an email at <a href= "mailto:support@wordpressmlm.com">support@wordpressmlm.com</a>', 'unilevel-mlm-pro'); ?>
                                    </td>	
                                </tr>	
				
                            </table>
                        </div>
                        <!-- end inside -->
                    </div>
                    <!-- END SUPPORT POSTBOX -->




                    <!-- BEGIN NEWS POSTBOX -->
                    <div class="postbox" id="wlrss-postbox">
                        <h3><?php _e('UNILEVEL MLM PRO NEWS', 'my-text-domain'); ?></h3>
                        <!-- begin inside content -->
                        <div class="inside wlrss-widget">
                         <?php
                            include_once( ABSPATH . WPINC . '/feed.php' );
                            $rss = fetch_feed(WP_BINARY_MLM_ULR.'/blog/unilevel-mlm-pro/feed');
                            if (!is_wp_error($rss)) : // Checks that the object is created correctly
                                $maxitems = $rss->get_item_quantity(5);
                                $rss_items = $rss->get_items(0, $maxitems);
                            endif;
                            ?>
                            <ul>
                                <?php if ($maxitems == 0) : ?>
                                    <li><?php _e('No items', 'my-text-domain'); ?></li>
                                <?php else : ?>
                                    <?php // Loop through each feed item and display each item as a hyperlink. ?>
                                    <?php foreach ($rss_items as $item) : ?>
                                        <li>
                                            <a href="<?php echo esc_url($item->get_permalink()); ?>" target="_blank"
                                               title="<?php printf(__('Posted %s', 'my-text-domain'), $item->get_date('j F Y | g:i a')); ?>">
                                                   <?php echo esc_html($item->get_title()); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>

                        </div>
                        <!-- end inside -->
                    </div>
                    <!-- END NEWS POSTBOX -->

                </div>
                <!-- END RIGHT POSTBOX CONTAINER -->

            </div><!-- END dashboard-widgets-wrap -->
        </div>
    </div>
    <?php
}

function mlm_licenese_settings_new() {
    $error = '';
    $msg = '';
    if (isset($_REQUEST['mlm_license_settings'])) {
        if ($_REQUEST['license_key'] != '') {
            $msg = licUpdate($_REQUEST);
        }
        else {
            $error = _e("<span style='color:red;'>Please fill the complete information.</span>");
        }
    }
    $settings = get_option('mlm_license_settings');
    if (isMLMLic() && empty($_POST)) {
        echo '<div class="notibar msgsuccess"><a class="close"></a><p>' . __('Thank you for re-validating your License Key.', 'unilevel-mlm-pro') . '</p></div>';
    }
    else if (empty($_POST)) {
        echo '<div class="notibar msgalert" id="revalidate" ><a class="close"></a><p>' . __('You will need to re-validate your license key due to the new licensing policy in this version of the plugin. Just click the Update Details below. You DO NOT need to generate a new license key.Your License key has been updated.', 'unilevel-mlm-pro') . '</p> </div>';
    }
    ?>
    <div>
        <?php if ($msg) : ?>	
            <?php _e($msg); ?>
        <?php endif; ?>
        <?php if ($error) : ?>
            <div class="notibar msgalert">
                <a class="close"></a>
                <p><?php _e($error); ?></p>
            </div>
        <?php endif; ?>
        <div id="license-form">
            <form name="frm" method="post" action="">
                <table>
                    <tr>
                        <td><strong><?php _e('Domain Name', 'unilevel-mlm-pro'); ?> :</strong></td>
                        <td><?php echo siteURL() ?></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td><strong><?php _e('License Key', 'unilevel-mlm-pro'); ?> :</strong></td>
                        <td><input type="text" name="license_key" size="30" value="<?php if (!empty($settings['license_key'])) _e($settings['license_key']); ?>" /></td>
                        <td><input type="submit" name="mlm_license_settings" id="mlm_license_settings" value="<?php _e('Update Details', 'unilevel-mlm-pro'); ?>" class='button-primary'></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <?php
}
?>
