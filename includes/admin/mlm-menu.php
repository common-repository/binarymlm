<?php
defined( 'ABSPATH' ) || exit;

/**
 * Main letscms_mlm_menu Class.
 *
 * @class letscms_mlm_menu
 */

class Letscms_MLM_Menu {

  /**
     * Hook in tabs.
     */
use letscms_mlmfunction;
public function __construct()
{ 
  add_action('admin_menu', array($this,'binarymlm_admin_menu' ) );
  add_action('admin_enqueue_scripts', array($this,'admin_mlm_enqueue_style') );
  add_action('admin_enqueue_scripts', array($this,'admin_mlm_enqueue_script') );

}

public function admin_mlm_enqueue_style() {
  wp_enqueue_style('mlm', MLM_URL . '/css/mlm.css');
  wp_enqueue_style('bootstrap-css', MLM_URL . '/css/bootstrap.css');
}

public function admin_mlm_enqueue_script() {
  wp_enqueue_script('mlm-form-validation', MLM_URL . '/includes/js/form-validation.js');
}

public function binarymlm_admin_menu(){
    /*
    1st argument: Title of the page
    2nd argument: Name of the menu
    3rd argument: The capability required for this menu to be displayed to the user.
    if 3rd arugment value is zero then this menu also accessible at user interface
    4ht argument: pass to the URL (name of the page)
    5th argument: function name (which function to be called)
    */

  $icon_url =  MLM_URL."/images/mlm_tree.png";
  add_menu_page(__('MLM-Settings','mlm'), __('Binary MLM','mlm'),'manage_options','binarymlm-dashboard-page', array('Letscms_MLM_Menu','adminMLMShowDashboard'), $icon_url);
  
  add_submenu_page('binarymlm-dashboard-page',__('Dashboard','mlm'),__('Dashboard','mlm'),'manage_options','binarymlm-dashboard-page',array('Letscms_MLM_Menu','adminMLMShowDashboard'));
  add_submenu_page('binarymlm-dashboard-page',__('Settings','mlm'),__('Settings','mlm'),'manage_options','binarymlm-admin-settings',array('Letscms_MLM_Menu','adminMLMSettings'));
 
  add_submenu_page('binarymlm-dashboard-page',__('Description','mlm'),__('Description','mlm'),'manage_options','binarymlm-description-page',array('Letscms_MLM_Menu','adminMLMDescription'));
  
  add_submenu_page('binarymlm-dashboard-page',__('Run Payouts','mlm'),__('Run Payouts','mlm'),'manage_options','binarymlm-payout',array('Letscms_MLM_Menu','adminMLMPayoutSettings'));
  add_submenu_page('binarymlm-dashboard-page',__('User Report','mlm'),__('User Report','mlm'),'manage_options','binarymlm-user-account',array('Letscms_MLM_Menu','adminMLMUserSettings'));

  add_submenu_page('binarymlm-dashboard-page', __('Withdrawals', 'mlm'), __('Withdrawals', 'mlm'), 'manage_options', 'binarymlm-admin-pending-withdrawal',array('Letscms_MLM_Menu','adminMLMUserWithdrawals'));
  add_submenu_page('binarymlm-dashboard-page',__('Reports','mlm'),__('Reports','mlm'),'manage_options','binarymlm-admin-reports',array('Letscms_MLM_Menu','adminReportsSettings'));
  add_submenu_page('binarymlm-dashboard-page', __('', 'mlm'), __('', 'mlm'), 'manage_options', 'binarymlm-admin-withdrawal-process', array('Letscms_MLM_Menu','adminMLMUserWithdrawalsProcess'));

}

public function adminMLMShowDashboard(){ 
      $MLM_Admin_Dashboard = new Letscms_MLM_Admin_Dashboard();
      $MLM_Admin_Dashboard->admindashboard();
}

public function adminMLMSettings() {
  global $pagenow, $wpdb;
  $mlm_settings = get_option('letscms_mlm_general_settings');
  $sql = "SELECT COUNT(*) AS num FROM {$wpdb->prefix}binarymlm_users ";
  $num = $wpdb->get_var($sql);
  if($num == 0)
   {
     $tabs = array(
             'createuser' => __('Create First User','mlm'),
             'general' => __('General','mlm'),
             'eligibility' => __('Eligibility','mlm'),
             'payout' => __('Payout','mlm'),
             'bonus' => __('Bonus','mlm'),
             'email' => __('Email Settings', 'mlm'),
             );
     if (isset($mlm_settings['binarymlm_ePin_activate']) && $mlm_settings['binarymlm_ePin_activate'] == '1') {
            $tabs['epin_settings'] = __('ePins', 'mlm');
            $tabs['product_price'] = __('Manage Products', 'mlm');
        }
      if (is_plugin_active('mlm-paypal-mass-pay/load-data.php')) {
        //plugin is activated
        $tabs['paypal_detail'] = __('Paypal Details', 'mlm');
       }

     $tabs['reset_all_data'] = __('Reset All MLM Data', 'mlm');
     $tabval = 'createuser';
     $tabfun = 'register_first_user';
   }else
     {
       $tabs = array(
               'general' => __('General','mlm'),
               'eligibility' => __('Eligibility','mlm'),
               'payout' => __('Payout','mlm'),
               'bonus' => __('Bonus','mlm'),
               'email' => __('Email Settings', 'mlm'),
               );
       if (isset($mlm_settings['binarymlm_ePin_activate']) && $mlm_settings['binarymlm_ePin_activate'] == '1') {
            $tabs['product_price'] = __('Manage Products', 'mlm');
            $tabs['epin_settings'] = __('ePins', 'mlm');
            
        }
       if (is_plugin_active('mlm-paypal-mass-pay/load-data.php')) {
            //plugin is activated
            $tabs['paypal_detail'] = __('Paypal Details', 'mlm');
        }

       $tabs['reset_all_data'] = __('Reset All MLM Data', 'mlm');
       $tabval = 'general';
       $tabfun = 'mlmGeneral';
     }
     
     if(!empty($_GET['tab'])){
     if( $pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'createuser' && !empty($_GET['tab']) && !empty($_GET['page']))
      $current = 'createuser';
     else if( $pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'general'  && !empty($_GET['tab']) && !empty($_GET['page']))
      $current = 'general';
     else if( $pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'eligibility'  && !empty($_GET['tab']) && !empty($_GET['page']))
      $current = 'eligibility';
     else if( $pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'payout'  && !empty($_GET['tab']) && !empty($_GET['page']))
      $current = 'payout';
     else if( $pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'bonus'  && !empty($_GET['tab']) && !empty($_GET['page']))
      $current = 'bonus';
     else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'email'  && !empty($_GET['tab']) && !empty($_GET['page']))
            $current = 'email';

     else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'epin_settings'  && !empty($_GET['tab']) && !empty($_GET['page']))
      $current = 'epin_settings';
     else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'product_price'  && !empty($_GET['tab']) && !empty($_GET['page']))
      $current = 'product_price';

     else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'paypal_detail'  && !empty($_GET['tab']) && !empty($_GET['page']))
      $current = 'paypal_detail';

     else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'reset_all_data'  && !empty($_GET['tab']) && !empty($_GET['page']))
      $current = 'reset_all_data';

}
   else
     $current = $tabval;
     $links = array();
    echo '<div class="wrap">';
    echo '<div id="icon-themes" class="icon32"></div>';
    echo "<h1>Binary MLM Settings</h1>";
    echo '<h2 class="nav-tab-wrapper">';
       foreach( $tabs as $tab => $name )
         {
           $class = ( $tab == $current ) ? ' nav-tab-active' : '';
           echo "<a class='nav-tab$class' href='?page=binarymlm-admin-settings&tab=$tab'>$name</a>";
         }
       echo '</h2>';
       echo '</div>';
    if(!empty($_GET['tab'])){
     if($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'createuser')
     {
     $MLM_Register_First_User= new Letscms_MLM_Register_First_User();
     $MLM_Register_First_User->register_first_user();
     }
     else if($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'general')
     {
     $MLM_General_Settings= new Letscms_MLM_General_Settings();
     $MLM_General_Settings->mlmGeneral();
     }
     else if($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'eligibility')
     {
     $MLM_Eligibility_Settings= new Letscms_MLM_Eligibility_Settings();
     $MLM_Eligibility_Settings->mlmEligibility();
     }
     else if($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'payout')
     {
     $MLM_Payout_Settings= new Letscms_MLM_Payout_Settings();
     $MLM_Payout_Settings->mlmPayout();
     }
     else if($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'bonus')
     {
     $MLM_Bonus_Settings= new Letscms_MLM_Bonus_Settings();
     $MLM_Bonus_Settings->mlmBonus();
     }
     else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'email')
     {
     $MLM_Email_Settings= new Letscms_MLM_Email_Settings();
     $MLM_Email_Settings->mlmEmailTemplates();
     }
     else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'epin_settings')
     {
     $MLM_ePin_Settings= new Letscms_MLM_ePin_Settings();
     $MLM_ePin_Settings->epin_tab();
     }
     else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'product_price')
     {
     $MLM_Product_Settings= new Letscms_MLM_Product_Settings();
     $MLM_Product_Settings->mlmProductPrice();
     }
     else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] == 'paypal_detail')
     {
     $MLM_Paypal_Settings= new Letscms_MLM_Paypal_Settings();
     $MLM_Product_Settings->Paypal_Detail();
     }
     else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-settings' && $_GET['tab'] =='reset_all_data')
     {
     $MLM_Reset_Settings= new Letscms_MLM_Reset_Settings();
     $MLM_Reset_Settings->adminMlmResetAllData();
     }
} else{
    $MLM_Register_First_User= new Letscms_MLM_Register_First_User();
    $MLM_General_Settings= new Letscms_MLM_General_Settings();
    if($num == 0)
      $MLM_Register_First_User->register_first_user();
    else
      $MLM_General_Settings->mlmGeneral();
}
}

public function adminMLMDescription(){
    global $wpdb;
    $MLM_Description = new Letscms_MLM_Description();
    $MLM_Description->mlmDescription();
}

public function adminMLMPayoutSettings() {
      $MLM_Run_Payouts = new Letscms_MLM_Run_Payouts();
      $MLM_Run_Payouts->adminMLMPayout();
}

public function adminMLMUserSettings() {
      $MLM_User_Report = new Letscms_MLM_User_Report();
      $MLM_User_Report->adminMLMUserAccount();
}

public function adminReportsSettings() {
      $MLM_User_Report = new Letscms_MLM_Admin_Reports();
      $MLM_User_Report->adminReports();
}

public function adminMLMUserWithdrawals(){ 
      $MLM_Withdrawal_Request = new Letscms_MLM_Withdrawal_Request();
      $MLM_Withdrawal_Request->mlm_withdrawal_request();
}

public function adminMLMUserWithdrawalsProcess(){
      $MLM_Withdrawal_process = new Letscms_MLM_Withdrawal_process();
      $MLM_Withdrawal_process->mlm_withdrawal_process();
}

}

return new Letscms_MLM_Menu();
