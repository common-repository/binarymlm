<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Admin_Dashboard {
	use letscms_mlmfunction;

	public function admindashboard(){
		$this->letscms_check_first_user();
		$BMP_Instance = new Letscms_BMP();
		
		?>
 	
 	<div class='wrap'>
    <div id="icon-users" class="icon32"></div>
    <h1><?php _e('Binary MLM Dashboard', 'mlm'); ?></h1>
        
        <div class="container1" >
        <div class="box1" ><h4 align="center">Binary MLM Menu</h4></div>
        <div class="wrap2">
                
                    <strong><?php $BMP_Instance->GetMenu('Settings', 'binarymlm-admin-settings', true); ?></strong> - <?php _e('Do the settings to work with Binary MLM Plugin', 'mlm'); ?><br /><br />
                    <strong><?php $BMP_Instance->GetMenu('Run Payouts', 'binarymlm-payout', true); ?></strong> - <?php _e('Distribute Commission & Bonus by running Payout in binary network.', 'mlm'); ?><br /><br />
                    <strong><?php $BMP_Instance->GetMenu('User Report', 'binarymlm-user-account', true); ?></strong> - <?php _e('See the report of all MLM users , like: Personal Details, Genealogy, Network Details, Payout Details.', 'mlm'); ?><br /><br />
                    <strong><?php $BMP_Instance->GetMenu('Withdrawals', 'binarymlm-admin-pending-withdrawal', true); ?></strong> - <?php _e('Manage the withdrawal request after receiving payout.', 'mlm'); ?><br /><br />
                    <strong><?php $BMP_Instance->GetMenu('Reports', 'binarymlm-admin-reports', true); ?></strong> - <?php _e('See the following details: Earning report, ePin report, Payout report, Withdrawal report.', 'mlm'); ?><br /><br />
                    <strong><?php $BMP_Instance->GetMenu('Description*', 'binarymlm-description-page', true); ?></strong> - <?php _e('See the documentation of Binary MLM.', 'mlm'); ?>
        </div>
        </div><br><br>

        <div class="container1" >
        <div class="box1" ><h4 align="center">Support</h4></div>
        <div class="wrap2">
                <?php
                $blog = Letscms_MLM_URL . "/blog/";
                ?>
                    <strong><a href="<?php echo $blog; ?>" target="_blank"><?php _e('Blog', 'mlm'); ?></a></strong> - <?php _e('Access the Blog on our website.', 'mlm'); ?><br /><br />
                    <strong><?php _e('Regular Support', 'mlm'); ?></strong> - <?php _e('Send us an email at <a href= "mailto:info@letscms.com">info@letscms.com</a>', 'mlm'); ?>

        </div>
        </div>



    </div>
    <?php
}
}
