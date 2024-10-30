<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_My_Payout {
use letscms_mlmfunction;

public function __construct()
  {
    add_action( 'wp_enqueue_scripts', array($this,'wp_mlm_enqueue_style') );
    add_action( 'wp_enqueue_scripts', array($this,'wp_mlm_enqueue_script') );
  }
  public function wp_mlm_enqueue_style() {
    wp_enqueue_style('mlm-css', MLM_URL . '/css/mlm.css');
    wp_enqueue_style('mlm-bootstrap-css', MLM_URL . '/css/bootstrap.css');
  }

  public function wp_mlm_enqueue_script() {
    wp_enqueue_script('mlm-form-validation', MLM_URL . '/includes/js/form-validation.js');
    wp_enqueue_script('jquery', 'https://www.google.com/jsapi', '', '', true);
  }


public function letscms_my_payout($id=''){
    $this->letscms_check_user($id);
if($id=='')
{
$detailsArr =  $this->letscms_my_payout_function();
$summaryArr =  $this->letscms_my_payout_summary_function();
}
else
{
$detailsArr =  $this->letscms_my_payout_function($id);
$summaryArr =  $this->letscms_my_payout_summary_function($id);
}

$mlm_settings = get_option('letscms_mlm_general_settings');
$page_id = $this->letscms_get_post_id(LETSCMS_MLM_MY_PAYOUT_DETAILS);
?>
 

 <table class="table" id="payout-page" style="width:90%;">
        <thead><tr>
            <td colspan="2"><strong><?php _e('Summaries', 'mlm'); ?></strong></td>
        </tr></thead>
        <tbody><tr>
            <td><?php _e('Total Amount Credited', 'mlm'); ?></td>
            <td><?php echo $mlm_settings['binarymlm_currency'] . ' '; ?><?php echo (!empty($summaryArr['total_amount'])) ? $summaryArr['total_amount'] : '0'; ?></td>
        </tr>
        <tr>
            <td><?php _e('Pending Payments', 'mlm'); ?></td>
            <td><?php echo $mlm_settings['binarymlm_currency'] . ' '; ?><?php echo (!empty($summaryArr['pending_amount'])) ? $summaryArr['pending_amount'] : '0'; ?></td>
        </tr>
        <tr>
            <td><?php _e('Processed Payments', 'mlm'); ?></td>
            <td><?php echo $mlm_settings['binarymlm_currency'] . ' '; ?><?php echo (!empty($summaryArr['processed_amount'])) ? $summaryArr['processed_amount'] : '0'; ?></td>
        </tr>
        <tr>
            <td><?php _e('Available Amount', 'mlm'); ?></td>
            <td><?php echo $mlm_settings['binarymlm_currency'] . ' '; ?><?php echo (!empty($summaryArr['available_amount'])) ? $summaryArr['available_amount'] : '0'; ?></td>
        </tr></tbody>
    </table>

<?php
if(count($detailsArr)>0){
$mlm_settings = get_option('letscms_mlm_general_settings');
?>
    <table class="table" id="payout-page" style="width:90%;">
        <thead>
            <tr>
                <td>Date</td><td>Amount</td><td>Status</td><td>Action</td>
            </tr>
        </thead>

        <?php foreach($detailsArr as $row) {

            $amount = ($row->total_amt <= $row->cap_limit || $row->cap_limit == 0.00) ? $row->capped_amt : $row->capped_amt . '(capped)';
                if ($row->withdrawal_initiated == 0 && $row->payment_processed == 0) {
                    $status = '<span style="color:#0DA443;">Available</span>';
                }
                else if ($row->withdrawal_initiated == 1 && $row->payment_processed == 0) {
                    $status = '<span style="color:#FF0000;">Pending</span>';
                }
                else if ($row->withdrawal_initiated == 1 && $row->payment_processed == 1) {
                    $status = '<span>Processed</span>';
                }
        ?>
        <tr>
            <td><?php echo $row->payoutDate ?></td>
            <td><?php echo $mlm_settings['binarymlm_currency'].' '.$amount ?></td>
            <td><?php echo $status ?></td>

            <?php if($id == ''){ ?>
              <td><a href="<?= $this->letscms_get_post_id_or_postname_for_payout(LETSCMS_MLM_MY_PAYOUT_DETAILS, $row->payout_id)?>" style="text-decoration:none;">View</a></td>
            <?php } else{ ?>
              <td><a href="?page=binarymlm-user-account&ac=payout-details&pid=<?php echo $row->payout_id; ?>" style="text-decoration:none;">View</a></td>
                <?php   }   ?>
    </tr>
    <?php } ?>
    </table>
    <?php }
    else{
    ?>
    <div class="no-payout"> You have not earned any commisssions yet. </div>
    <?php
    }
}// end of function letscms_my_payout()

function letscms_my_payout_function($id='')
{
 global $wpdb,$current_user;
 if($id == "")
 $userId = $current_user->ID;
 else
 $userId = $id;

 $sql = "SELECT MU.id AS id FROM {$wpdb->prefix}users AS U JOIN {$wpdb->prefix}binarymlm_users AS MU ON MU.username=U.user_login WHERE U.ID='".$userId."'";
 $res = $wpdb->get_var($sql);
 $mlm_user_id = $res;
 if ( is_user_logged_in())
 {
   $sql = "SELECT id, user_id, DATE_FORMAT(`date`,'%d %b %Y') as payoutDate, payout_id, commission_amount, referral_commission_amount, capped_amt, bonus_amount, tax, service_charge FROM {$wpdb->prefix}binarymlm_payout WHERE user_id = '".$mlm_user_id."' ORDER BY payoutDate";
   $myrows = $wpdb->get_results($sql);
 }
 return $myrows;
}


function letscms_my_payout_summary_function($id = '') {
    global $wpdb;
    global $current_user;
    get_currentuserinfo();
    if ($id == "") {
        $userId = $current_user->ID;
    }
    else {
        $userId = $id;
    }
    $sql = "SELECT {$wpdb->prefix}binarymlm_users.id AS id FROM {$wpdb->prefix}users,{$wpdb->prefix}binarymlm_users WHERE {$wpdb->prefix}binarymlm_users.username = {$wpdb->prefix}users.user_login AND {$wpdb->prefix}users.ID = '$userId'";
    $res = $wpdb->get_results($sql, ARRAY_A);
    if (!empty($res))
        $mlm_user_id = $res[0]['id'];
    else
        $mlm_user_id = '';
    $myrows = array();

    if (is_user_logged_in()) {
        // total payout amount calculate
        $sql = "SELECT SUM(capped_amt) AS total FROM {$wpdb->prefix}binarymlm_payout WHERE user_id ='$mlm_user_id'";
        $total_amount = $wpdb->get_var($sql);
        $myrows['total_amount'] = $total_amount;

        // Pending Payments amount calculate    
        $sql1 = "SELECT SUM(capped_amt) AS total FROM {$wpdb->prefix}binarymlm_payout WHERE withdrawal_initiated='1' AND payment_processed='0' AND user_id ='$mlm_user_id'";
        $pending_amount = $wpdb->get_var($sql1);
        $myrows['pending_amount'] = $pending_amount;


        // Processed Payments amount calculate  
        $sql2 = "SELECT SUM(capped_amt) AS total FROM {$wpdb->prefix}binarymlm_payout WHERE withdrawal_initiated='1' AND payment_processed='1' AND user_id ='$mlm_user_id'";
        $processed_amount = $wpdb->get_var($sql2);
        $myrows['processed_amount'] = $processed_amount;

        // Available Amount amount calculate    
        $sql3 = "SELECT SUM(capped_amt) AS total FROM {$wpdb->prefix}binarymlm_payout WHERE withdrawal_initiated='0' AND payment_processed='0' AND user_id ='$mlm_user_id'";
        $available_amount = $wpdb->get_var($sql3);
        $myrows['available_amount'] = $available_amount;
    }

    return $myrows;
}
}//end of class