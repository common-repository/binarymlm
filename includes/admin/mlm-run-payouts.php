<?php
defined( 'ABSPATH' ) || exit;
class Letscms_MLM_Run_Payouts{
  use letscms_mlmfunction;
  public function adminMLMPayout(){
  $msg = '';
  $chk = 'error';
  $displayData = '';
  $this->letscms_check_first_user();

  $payout_settings = get_option('letscms_mlm_payout_settings');
  if ($payout_settings['binarymlm_service_charge_type'] == 'Fixed')
        $sct = 'Fixed';
  if ($payout_settings['binarymlm_service_charge_type'] == 'Percentage')
        $sct = '%';

  if(isset($_REQUEST['distribute_commission_bonus']))
    {
      $chk = '';
      $msg .= $this->letscms_mlmDistributeCommission();
      $msg .= ' & ';
      $msg .= $this->letscms_mlmDistributeBonus();
      $msg .= '&nbsp;Distributed Successfully';
      $msg .= '</br>Now you can <b>Run Payout</b>.';
    }
  if(isset($_REQUEST['pay_cycle']))
    {
      $payoutArr = $this->letscms_payoutRun();
    }

  if(isset($_REQUEST['pay_actual_amount']))
    {
      $chk = '';
      $msg = $this->letscms_run_pay_cycle();
    }

?>
<div class='wrap'>
<div id="icon-users" class="icon32"></div><h1>Binary MLM Payout</h1>
<?php 
  if($chk=='')  { echo "<div class='notibar msgsuccess'><p>" . $msg . "</p></div>"; } 
?>

<div class="container1" >
  <div class="box1" ><h4 align="center">Run Binary MLM Payout</h4></div>
    <div style="font-size:18px; padding:10px; color:#008000; ">
      <?php if(isset($payoutArr['directRun'])){ echo $payoutArr['directRun']; } ?>
    </div>
    
    <form name="frm" method="post" action="">
        <div class="col-md-12">
          <label>To distribute commission and bonus, Click here :</label><br>
          <button type="submit" name="distribute_commission_bonus" id="distribute_commission_bonus" class="btn btn-primary btn-default">Distribute Commission and Bonus &raquo;</button>
        </div><br>

        <div class="col-md-12">
          <label>To run payout , Click here :</label><br>
          <button type="submit" name="pay_cycle" id="pay_cycle" class="btn btn-primary btn-default">Run Payout Routine &raquo;</button>
        </div><br>
      <!-- Dislay data -->
      <?php if(isset($payoutArr['directRun']) && $payoutArr['displayData'] != '')
      {?>
        <div class="col-md-12">
          <table class="table table-bordered">
            <thead><tr>
              <th class="text-center">S.No</th>
              <th class="text-center">Username</th>
              <th class="text-center">Name</th>
              <th class="text-center">Commission</th>
              <th class="text-center">Referal commision</th>
              <th class="text-center">Bonus</th>
              <th class="text-center">Cap Limit</th>
              <th class="text-center">Service Charge</th>
              <th class="text-center">Tax</th>
              <th class="text-center">Net Amount</th>
            </tr></thead>

    <?php
    if($payoutArr['displayData'] != 'None'){
        $i = 1;
          $listArr[-1]['username'] = __('Username', 'mlm');
          $listArr[-1]['name'] = __('Name', 'mlm');
          $listArr[-1]['commission'] = __('Commission', 'mlm');
          $listArr[-1]['dirRefcommission'] = __('Referral commision', 'mlm');
          $listArr[-1]['bonus'] = __('Bonus', 'mlm');
          $listArr[-1]['cap_limit'] = __('Cap Limit', 'mlm');
          $listArr[-1]['service_charge'] = __('Service Charge', 'mlm');
          $listArr[-1]['tax'] = __('Tax', 'mlm');
          $listArr[-1]['net_amount'] = __('Net Amount', 'mlm');
      foreach( $payoutArr['displayData'] as $row)
    {
      ?>
      <tr>
      <td class="text-center"><?= $i; ?></td>
      <td class="text-center"><?= $row['username']; ?></td>
      <td class="text-center"><?= $row['first_name']." ".$row['last_name']; ?></td>
      <td class="text-center"><?= number_format($row['commission'], 2, '.',''); ?></td>
      <td class="text-center"><?= number_format($row['dirRefcommission'], 2, '.',''); ?></td>
      <td class="text-center"><?= number_format($row['bonus'], 2, '.',''); ?></td>
      <td align="center"><?php echo ($row['total_amt'] >= $row['cap_limit']) ? $row['cap_limit'] : 'N/A'; ?></td>
      <td class="text-center"><?= number_format($row['service_charge'], 2, '.',''); ?></td>
      <td class="text-center"><?= number_format($row['tax'], 2, '.',''); ?></td>
      <td align="center"><?php echo 
        ($row['total_amt'] >= $row['cap_limit'] && !empty($row['cap_limit'])) ?
            ($row['cap_limit'] == '0.00' ? number_format($row['net_amount'], 2, '.', '') : number_format($row['net_amount'], 2, '.', '') . '(capped)') : number_format($row['net_amount'], 2, '.', '');
      ?></td></tr>
      <?php
      $listArr[$i]['username'] = $row['username'];
      $listArr[$i]['name'] = $row['first_name'] . " " . $row['last_name'];
      $listArr[$i]['commission'] = number_format($row['commission'], 2, '.', '');
      $listArr[$i]['dirRefcommission'] = number_format($row['dirRefcommission'], 2, '.', '');
      $listArr[$i]['bonus'] = number_format($row['bonus'], 2, '.', '');
      $listArr[$i]['cap_limit'] = ($row['total_amt'] >= $row['cap_limit']) ? $row['cap_limit'] : 'N/A';
      $listArr[$i]['service_charge'] = number_format($row['service_charge'], 2, '.', '');
      $listArr[$i]['tax'] = number_format($row['tax'], 2, '.', '');
      $listArr[$i]['net_amount'] = ($row['total_amt'] >= $row['cap_limit'] && !empty($row['cap_limit'])) ?
              ($row['cap_limit'] == '0.00' ? number_format($row['net_amount'], 2, '.', '') : number_format($row['net_amount'], 2, '.', '') . '(capped)') : number_format($row['net_amount'], 2, '.', '');

           $i++;
        }
        }else{
      ?>
      <tr>
        <td colspan="10" class="text-center">There is no any eligible member Found in this Payout. </td>
      </tr>
      <?php
      }
    ?>
     </table><br>
    <div  class="pull-right">
      <button type="submit" name="pay_actual_amount" id="pay_actual_amount" class="btn btn-primary btn-default" <?php echo ($payoutArr['displayData'] != 'None') ? '' : 'disabled'; ?>>All is Well. Commit. &raquo;</button><br><br>
      <a href="?page=binarymlm-payout" ><?php _e('Something\'s wrong. Cancel.'); ?></a>
    </div></form>
        <?php
        $value = !empty($listArr)?serialize($listArr):'';
        ?>
            <form method="post" action="<?php echo plugins_url() ?>/binarymlm/includes/admin/export-file.php">
              <input type="hidden" name ="listarray" value='<?php echo $value ?>' />
              <input type="hidden" name ="filename" value='payout-list-report' />
              <input type="hidden" name ="pay_cycle" value='Run Payout Routine' />
              <button type="submit" name="export_csv" id="export_csv" class="btn btn-primary btn-default">Export to CSV &raquo;</button>
            </form>
        <?php }
      ?>
      <!-- End display data -->
      <div style="clear:both;"></div>
    
</div></p></p>
<?php
  }

  /***************** Begin the code for calculate and distribute the commission***********************/
  function letscms_mlmDistributeCommission()
  {
    global $wpdb;
    //select all active users and give commision to their parents
    $sql="SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE payment_status= '0' OR payment_status= '1' OR payment_status= '2' AND banned = '0' ORDER BY id";
    
    $results = $wpdb->get_results($sql);
    
    $num = $wpdb->num_rows;
    if($num)
    {
      foreach($results as $data)
      {
        if($this->letscms_mlmIsEligibleForCommission($data->user_key)){
           $this->letscms_mlmCalculateCommission($data->user_key); }
      }
    }
    return "Commission";
  }

  /***************** Begin the code for calculate and distribute the bonus***********************/
  function letscms_mlmDistributeBonus()
  {
    global $wpdb;
    //select all active users and give commision to their parents
    $sql="SELECT user_key FROM {$wpdb->prefix}binarymlm_users WHERE payment_status= '0' OR payment_status= '1' OR         payment_status= '2' AND banned = '0' ORDER BY id";
    
    $query = $wpdb->get_results($sql);
    
    $num = $wpdb->num_rows;
      if($num)
      {
        foreach($query as $result)
        {
          if($this->letscms_mlmIsEligibleForCommission($result->user_key))
            $this->letscms_mlmCalculateBonus($result->user_key);
        }
      }
      return "Bonus";
  }

  
  /***************** Begin the code for for displaying payout detail***********************/
  function wpmlm_run_pay_display_functions()
  {
    global $wpdb;
    $sql=  "SELECT id FROM {$wpdb->prefix}binarymlm_users 
            WHERE id IN(SELECT parent_id AS id FROM {$wpdb->prefix}binarymlm_commission 
            WHERE payout_id =0 
            UNION 
            SELECT sponsor_id AS id FROM {$wpdb->prefix}binarymlm_referral_commission   
            WHERE payout_id =0 
            UNION 
            SELECT mlm_user_id AS id FROM {$wpdb->prefix}binarymlm_bonus 
            WHERE payout_id =0 )";
    $rs = $wpdb->get_results($sql);

    if($wpdb->num_rows > 0)
    {
      $i = 0;
      foreach($rs as $row)
      {
            $userId = $row->id; 
            $commissionAmt = $this->letscms_getCommissionById($userId);
            $directReffComm = $this->letscms_getReferralCommissionById($userId);
            $bonusAmt = $this->letscms_getBonusAmountById($userId);
            $totalAmt = $commissionAmt + $directReffComm + $bonusAmt;
            $payout_settings = get_option('letscms_mlm_payout_settings');
            $tax = $payout_settings['binarymlm_tds'];
            $service_charge = $payout_settings['binarymlm_service_charge'];
            $capLimitAmt = !empty($payout_settings['binarymlm_cap_limit_amount']) ? $payout_settings['binarymlm_cap_limit_amount'] : '';
            if ($totalAmt <= $capLimitAmt) {
                $total = $totalAmt;
            }
            else {
                $total = empty($capLimitAmt) ? $totalAmt : ($capLimitAmt == '0.00' ? $totalAmt : $capLimitAmt);
            }
            $taxAmt = round(($total) * $tax / 100, 2);
            if ($payout_settings['binarymlm_service_charge_type'] == 'Fixed')
                $service_charge = $service_charge;
            if ($payout_settings['binarymlm_service_charge_type'] == 'Percentage')
                $service_charge = round(($total) * $service_charge / 100, 2);
            $user_info = $this->letscms_getUserInfoByMlmUserId($userId);

            $displayDataArray[$i] ['username'] = $user_info->user_login;
            $displayDataArray[$i] ['first_name'] = $user_info->first_name;
            $displayDataArray[$i] ['last_name'] = $user_info->last_name;
            $displayDataArray[$i] ['commission'] = $this->letscms_getCommissionById($userId);
            $displayDataArray[$i] ['dirRefcommission'] = $directReffComm;
            $displayDataArray[$i] ['total_amt'] = $totalAmt;
            $displayDataArray[$i] ['cap_limit'] = $capLimitAmt;
            $displayDataArray[$i] ['bonus'] = $bonusAmt;
            $displayDataArray[$i] ['tax'] = $taxAmt;
            $displayDataArray[$i] ['service_charge'] = $service_charge == "" ? 0.00 : $service_charge;
            $displayDataArray[$i] ['net_amount'] = round(($total - $service_charge - $taxAmt));
            $i++;
      }
    }
    else{
          $displayDataArray = "None"; }

    return $displayDataArray;
  }


/******************************Pay Actual Amount by clicking on commit **************************************/
function letscms_run_pay_cycle()
{
  $returnVar  = $this->wpmlm_run_pay_cycle_functions();
  return $returnVar;
}

function wpmlm_run_pay_cycle_functions()
{
$payoutMasterId = $this->createPayoutMaster();

global $wpdb;
$sql=  "SELECT id FROM {$wpdb->prefix}binarymlm_users WHERE id IN(SELECT parent_id AS id FROM {$wpdb->prefix}binarymlm_commission WHERE payout_id =0 UNION SELECT sponsor_id AS id FROM {$wpdb->prefix}binarymlm_referral_commission 
  WHERE payout_id =0 UNION SELECT mlm_user_id AS id FROM {$wpdb->prefix}binarymlm_bonus WHERE payout_id =0 )";

$rs = $wpdb->get_results($sql);

  if($wpdb->num_rows > 0)
  {
    foreach($rs as $row)
    {
            $userId = $row->id;
            $commissionAmt = $this->letscms_getCommissionById($userId);
            $directReffComm = $this->letscms_getReferralCommissionById($userId);
            $bonusAmt = $this->letscms_getBonusAmountById($userId);
            $totalAmt = $commissionAmt + $directReffComm + $bonusAmt;
            $payout_settings = get_option('letscms_mlm_payout_settings');
            $tax = $payout_settings['binarymlm_tds'];
            $capLimitAmt = !empty($payout_settings['binarymlm_cap_limit_amount']) ? $payout_settings['binarymlm_cap_limit_amount'] : '';
            $service_charge = $payout_settings['binarymlm_service_charge'];
            if ($totalAmt <= $capLimitAmt) {
                $sub_total = $totalAmt;
            }
            else {
                $sub_total = empty($capLimitAmt) ? $totalAmt : ($capLimitAmt == '0.00' ? $totalAmt : $capLimitAmt);
            }


            if ($payout_settings['binarymlm_service_charge_type'] == 'Fixed')
                $service_charge = $service_charge;
            if ($payout_settings['binarymlm_service_charge_type'] == 'Percentage')
                $service_charge = round(($sub_total) * $service_charge / 100, 2);

            $taxAmt = round(($sub_total) * $tax / 100, 2);
            $netAmt = round($sub_total - $taxAmt - $service_charge);
            $user_info = $this->letscms_getUserInfoByMlmUserId($userId);


      /***********************************************************
      INSERT INTO PAYOUT TABLE
      ***********************************************************/
      $sql_payout = "INSERT INTO {$wpdb->prefix}binarymlm_payout(user_id, date, payout_id, commission_amount,referral_commission_amount,bonus_amount,total_amt,capped_amt,cap_limit,tax, service_charge) VALUES ('".$userId."', '".date('Y-m-d H:i:s')."', '".$payoutMasterId."', '".$commissionAmt."','".$directReffComm."','".$bonusAmt."','" . $totalAmt . "','" . $netAmt . "', '" . $capLimitAmt . "',  '".$taxAmt."', '".$service_charge."')";

      $wpdb->query($sql_payout);

      $wpdb->query("UPDATE {$wpdb->prefix}binarymlm_referral_commission set payout_id='$payoutMasterId' where sponsor_id='$userId' AND payout_id=0");

            
      $insert_id = $wpdb->get_var("select id from {$wpdb->prefix}binarymlm_payout order by id DESC limit 1");
            if ($u = get_option('letscms_mlm_payout_mail', true) == 1) {
                $this->PayoutGeneratedMail($userId, $netAmt, $payoutMasterId);
            }

      /* Process withdrawal Automatically start */
        $mlm_general_settings = get_option('letscms_mlm_general_settings');
        if ($mlm_general_settings['process_withdrawal'] == 'Automatically') {
            $sql = "UPDATE {$wpdb->prefix}binarymlm_payout SET `withdrawal_initiated`=1,  `withdrawal_initiated_date` = NOW() WHERE `user_id` = '" . $userId . "' AND `id`='" . $insert_id . "'";
            $wpdb->query($sql);
            $this->WithDrawalInitiatedMail($userId, 'During payout Distribution', $payoutMasterId);
        }

      /***********************************************************
      Update Commission table Payout Id
      ***********************************************************/
      if(isset($insert_id) && $insert_id >0)
      {
        $sql_comm = "UPDATE {$wpdb->prefix}binarymlm_commission SET payout_id= '" . $payoutMasterId . "' WHERE parent_id = '" . $userId . "' AND payout_id = '0'";
        $rs_comm = $wpdb->query($sql_comm);
      }
      /***********************************************************
      Update Bonus table Payout Id
      ***********************************************************/
      if(isset($insert_id) && $insert_id >0)
      {
        $sql_bon = "UPDATE {$wpdb->prefix}binarymlm_bonus SET payout_id= '" . $payoutMasterId . "' WHERE mlm_user_id = '" . $userId . "' AND payout_id = '0'";
        $rs_bon = $wpdb->query($sql_bon);
      }
    }
  }
  return "Payout Run Successfully";
}

function createPayoutMaster()
{
    global $wpdb;
    $mlm_payout = get_option('letscms_mlm_payout_settings');
    $capLimitAmt = $mlm_payout['cap_limit_amount'];
    $sql = "INSERT INTO {$wpdb->prefix}binarymlm_payout_master(date,cap_limit) VALUES ('" . current_time('mysql') . "','$capLimitAmt')";
    $wpdb->query($sql);
    $pay_master_id = $wpdb->get_var("select id from {$wpdb->prefix}binarymlm_payout_master order by id DESC limit 1");
    return $pay_master_id;
}
}
