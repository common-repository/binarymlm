<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Admin_Reports{
 use letscms_mlmfunction;
    public function adminReports() {
    $this->letscms_check_first_user();
    global $pagenow, $wpdb;
    $general_settings = get_option('letscms_mlm_general_settings'); 
    if (!empty($general_settings['binarymlm_product_price'])) {
        $tabs = array(
            'earningreports' => __('Earning Reports', 'mlm'),
            'epinreports' => __('ePin Report', 'mlm'),
            'withdrawalreport' => __('Withdrawal Report', 'mlm'),
            'payoutreports' => __('Payout Reports', 'mlm'),
        );

        $tabval = 'erningreports';
        $tabfun = 'earningReports';
    }
    else {
        $tabs = array(
            'epinreports' => __('ePin Report', 'mlm'),
            'withdrawalreport' => __('Withdrawal Report', 'mlm'),
            'payoutreports' => __('Payout Reports', 'mlm'),
        );

        $tabval = 'epinreports';
        $tabfun = 'adminMLMePinsReports';
    }

    if (!empty($_GET['tab'])) {
        if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-reports' && $_GET['tab'] == 'earningreports')
            $current = 'earningreports';
        else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-reports' && $_GET['tab'] == 'epinreports')
            $current = 'epinreports';
        else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-reports' && $_GET['tab'] == 'withdrawalreport')
            $current = 'withdrawalreport';
        else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-reports' && $_GET['tab'] == 'payoutreports')
            $current = 'payoutreports';
    }
    else{ 
        $current = $tabval;
    }

    $links = array();
?>
    <div class='wrap'>
    <div id="icon-themes" class="icon32"></div>
    <h1><?php _e('Binary MLM Reports', 'mlm'); ?></h1>

    <?php
    echo '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        _e("<a class='nav-tab$class' href='?page=binarymlm-admin-reports&tab=$tab'>$name</a>");
    }
    echo '</h2>';


    if (!empty($_GET['tab'])) {
        if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-reports' && $_GET['tab'] == 'earningreports')
            $this->earningReports();
        else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-reports' && $_GET['tab'] == 'epinreports')
            $this->adminMLMePinsReports();
        else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-reports' && $_GET['tab'] == 'withdrawalreport')
            $this->adminMLMSucessWithdrawals();
        else if ($pagenow == 'admin.php' && $_GET['page'] == 'binarymlm-admin-reports' && $_GET['tab'] == 'payoutreports')
            $this->adminMLMPayoutReports();
    }
    else{
        $this->$tabfun();
    }
}

public function earningReports() {
    require_once('earning-reports.php');
    $MLM_Earning_Report = new Letscms_MLM_Earning_Report();
    $MLM_Earning_Report->earning_report();
}


/**************ePins reports ***************/

public function adminMLMePinsReports() {
        $MLM_ePin_Reports= new Letscms_MLM_ePin_Reports();
        $MLM_ePin_Reports->mlmePinReports();
    
}


/*****************Payout Report********************/
public function adminMLMPayoutReports() {
        $MLM_Payout_Reports= new Letscms_MLM_Payout_Reports();
        $MLM_Payout_Reports->mlmPayoutReports();
    
}

public function adminMLMSucessWithdrawals(){
        $MLM_Withdrawal_Success = new Letscms_MLM_Withdrawal_Success();
        $MLM_Withdrawal_Success->mlm_withdrawal_sucess();
}
}
