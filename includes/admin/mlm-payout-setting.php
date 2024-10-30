<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Payout_Settings{
  use letscms_mlmfunction;
  /*************************************Payout Settings*****************************************/
  public function mlmPayout(){
    $error = array();
    $chk = 'error';
    $this->letscms_check_first_user();
    //most outer if condition
    if(isset($_POST['binarymlm_payout_settings']))
    {
      $pair1 = sanitize_text_field( $_POST['binarymlm_pair1'] );
      $pair2 = sanitize_text_field( $_POST['binarymlm_pair2'] );
      $initial_pair = sanitize_text_field( $_POST['binarymlm_initial_pair'] );
      $initial_amount = sanitize_text_field( $_POST['binarymlm_initial_amount'] );
      $further_amount = sanitize_text_field( $_POST['binarymlm_further_amount'] );
      
      if ( $this->letscms_checkPair($pair1, $pair2) )
        $error[] = __("Your pair ratio is wrong.",'mlm');

      if ( $this->letscms_checkInputField($initial_pair) )
        $error[] = __("Your initial pair value is wrong.",'mlm');

      if ( $this->letscms_checkInputField($initial_amount) )
        $error[] = __("Your initial amount value is wrong.",'mlm');

      if ( $this->letscms_checkInitial($further_amount) )
        $error[] = __("Your further amount value is wrong.",'mlm');

      if(empty($error))
      {
        $chk = '';
        update_option('letscms_mlm_payout_settings', $_POST);
        $url = get_bloginfo('url')."/wp-admin/admin.php?page=binarymlm-admin-settings&tab=bonus";
        $msg = "<div class='notibar msgsuccess'><p>Your payout settings have been successfully updated.</p></div>";
       
      }
    }// end outer if condition
    if($chk!='')
    {
      $mlm_settings = get_option('letscms_mlm_payout_settings');
      ?>
  <div class='wrap1'>
  
  <?php if($error) {?>
  <div class="notibar msgerror">
    <p> <strong>Please Correct the following Error :</strong> <?php foreach($error as $err){ echo $err."</br>"; } ?></p>
  </div>
  <?php }

    $pair1 = (isset($_POST['binarymlm_pair1']) ? $_POST['binarymlm_pair1'] : (isset($mlm_settings['binarymlm_pair1']) ? $mlm_settings['binarymlm_pair1'] : ''));
    $pair2 = (isset($_POST['binarymlm_pair2']) ? $_POST['binarymlm_pair2'] : (isset($mlm_settings['binarymlm_pair2']) ? $mlm_settings['binarymlm_pair2'] : ''));
    $initial_pair = (isset($_POST['binarymlm_initial_pair']) ? $_POST['binarymlm_initial_pair'] : (isset($mlm_settings['binarymlm_initial_pair']) ? $mlm_settings['binarymlm_initial_pair'] : ''));
    
    $initial_amount = (isset($_POST['binarymlm_initial_amount']) ? $_POST['binarymlm_initial_amount'] : (isset($mlm_settings['binarymlm_initial_amount']) ? $mlm_settings['binarymlm_initial_amount'] : ''));
    $init_pair_comm_type = (isset($_POST['binarymlm_init_pair_comm_type']) ? $_POST['binarymlm_init_pair_comm_type'] : (isset($mlm_settings['binarymlm_init_pair_comm_type']) ? $mlm_settings['binarymlm_init_pair_comm_type'] : ''));
    
    $further_amount = (isset($_POST['binarymlm_further_amount']) ? $_POST['binarymlm_further_amount'] : (isset($mlm_settings['binarymlm_further_amount']) ? $mlm_settings['binarymlm_further_amount'] : ''));
    $furt_amou_comm_type = (isset($_POST['binarymlm_furt_amou_comm_type']) ? $_POST['binarymlm_furt_amou_comm_type'] : (isset($mlm_settings['binarymlm_furt_amou_comm_type']) ? $mlm_settings['binarymlm_furt_amou_comm_type'] : ''));
    
    $referral_commission_amount = (isset($_POST['binarymlm_referral_commission_amount']) ? $_POST['binarymlm_referral_commission_amount'] : (isset($mlm_settings['binarymlm_referral_commission_amount']) ? $mlm_settings['binarymlm_referral_commission_amount'] : ''));
    $dir_ref_comm_type = (isset($_POST['binarymlm_dir_ref_comm_type']) ? $_POST['binarymlm_dir_ref_comm_type'] : (isset($mlm_settings['binarymlm_dir_ref_comm_type']) ? $mlm_settings['binarymlm_dir_ref_comm_type'] : ''));
    
    $service_charge = (isset($_POST['binarymlm_service_charge']) ? $_POST['binarymlm_service_charge'] : (isset($mlm_settings['binarymlm_service_charge']) ? $mlm_settings['binarymlm_service_charge'] : ''));
    $service_charge_type = (isset($_POST['binarymlm_service_charge_type']) ? $_POST['binarymlm_service_charge_type'] : (isset($mlm_settings['binarymlm_service_charge_type']) ? $mlm_settings['binarymlm_service_charge_type'] : ''));
    
    $tds = (isset($_POST['binarymlm_tds']) ? $_POST['binarymlm_tds'] : (isset($mlm_settings['binarymlm_tds']) ? $mlm_settings['binarymlm_tds'] : ''));
    $cap_limit_amount = (isset($_POST['binarymlm_cap_limit_amount']) ? $_POST['binarymlm_cap_limit_amount'] : (isset($mlm_settings['binarymlm_cap_limit_amount']) ? $mlm_settings['binarymlm_cap_limit_amount'] : ''));
?>
<div class="container1" >
<div class="box1" ><h4 align="center">Binary MLM Payout Settings</h4></div>
<div class="wrap2">
<form role="form" name="binarymlm_admin_payout_settings" method="post" action="">
  <div class="col-md-12">
    <div class="row">
    <div class="col-md-6">
    <div class="form-group">
      <label><a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('binarymlm-admin-pair');"><?php _e('Pair', 'mlm'); ?> <span style="color:red;">*</span>: </a> </label>
    <div class="row">
       <div class="col-md-3"> <input class="form-control" type="text" name="binarymlm_pair1" id="binarymlm_pair1" size="2" value="<?php echo $pair1 ?>"> </div> :
       <div class="col-md-3"> <input class="form-control" type="text" name="binarymlm_pair2" id="binarymlm_pair2" size="2" value="<?php echo $pair2 ?>"></div>      
    </div><div class="toggle-visibility" id="binarymlm-admin-pair"><?php _e('Please mention here paid members in right and left leg.', 'mlm'); ?></div>
    </div>
    </div>

    <div class="col-md-6">
    <div class="form-group">
      <label><a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm-admin-initial-pair');"><?php _e('Initial Pairs', 'mlm'); ?> <span style="color:red;">*</span>: </a> </label>
      <input class="form-control" type="text" name="binarymlm_initial_pair" id="binarymlm_initial_pair" size="2" value="<?php echo $initial_pair ?>"><div class="toggle-visibility" id="binarymlm-admin-initial-pair"><?php _e('Please mention here initial pair.', 'mlm'); ?></div>
    </div>
    </div></div>

    <div class="row">
    <div class="col-md-6">
    <div class="form-group">
      <label><a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm-admin-mlm-initial-amount');"><?php _e('Initial Pair Amount', 'mlm'); ?> <span style="color:red;">*</span>: </a> </label>
      <div class="row">
    <div class="col-md-3"><input type="text" name="binarymlm_initial_amount" id="binarymlm_initial_amount" size="10" value="<?php echo $initial_amount ?>"></div>
    <div class="col-md-3"><select name="binarymlm_init_pair_comm_type" id="binarymlm_init_pair_comm_type" required>
                                <option value="">Select type</option>
                                <option value="Fixed" <?php echo $init_pair_comm_type == 'Fixed' ? 'selected="selected"' : '' ?>  selected="selected">Fixed</option>
                                <option value="Percentage" <?php echo $init_pair_comm_type == 'Percentage' ? 'selected="selected"' : '' ?>>Percentage</option>
                            </select>
    <div class="toggle-visibility" id="binarymlm-admin-initial-amount"><?php _e('Please mention here initial amount.', 'mlm'); ?></div></div>
      </div>
    </div>
    </div>

    <div class="col-md-6">
    <div class="form-group">
      <label><a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm-admin-further-amount');"><?php _e('Further Pair Amount', 'mlm'); ?> <span style="color:red;">*</span>: </a> </label>
      <div class="row">
    <div class="col-md-3"><input type="text" name="binarymlm_further_amount" id="binarymlm_further_amount" size="10" value="<?php echo $further_amount ?>"></div>
    <div class="col-md-3"><select name="binarymlm_furt_amou_comm_type" id="binarymlm_furt_amou_comm_type" required >
                                <option value="">Select type</option>
                                <option value="Fixed" <?php echo $furt_amou_comm_type == 'Fixed' ? 'selected="selected"' : '' ?>  selected="selected" >Fixed</option>
                                <option value="Percentage" <?php echo $furt_amou_comm_type == 'Percentage' ? 'selected="selected"' : '' ?>>Percentage</option>
                            </select>
    <div class="toggle-visibility" id="binarymlm-admin-further-amount"><?php _e('Please mention here further pair amount.', 'mlm'); ?></div></div>
    </div>
    </div>
    </div></div>

    <div class="row">
    <div class="col-md-6">
    <div class="form-group">
      <label><a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm-admin-direct-referal-commission');"><?php _e('Direct Referral Commission', 'mlm'); ?>:</a>
                        </th> </label>
    <div class="row">
    <div class="col-md-3"><input type="text" name="binarymlm_referral_commission_amount" id="binarymlm_referral_commission_amount" size="10" value="<?php echo $referral_commission_amount ?>"></div>
    <div class="col-md-3"><select name="binarymlm_dir_ref_comm_type" id="binarymlm_dir_ref_comm_type" >
                                <option value="">Select type</option>
                                <option value="Fixed" <?php echo $dir_ref_comm_type == 'Fixed' ? 'selected="selected"' : '' ?> selected="selected">Fixed</option>
                                <option value="Percentage" <?php echo $dir_ref_comm_type == 'Percentage' ? 'selected="selected"' : '' ?>>Percentage</option>
                            </select> 
    <div class="toggle-visibility" id="binarymlm-admin-direct-referal-commission"><?php _e('Please specify referral_commission_amount.', 'mlm'); ?></div></div>
    </div>
    </div>
    </div>

    <div class="col-md-6">
    <div class="form-group">
      <label> <a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm-admin-service-charege');">
                                <?php _e('Service Charge (If any)', 'mlm'); ?> :</a></label>
      <div class="row">
    <div class="col-md-3"><input type="text" name="binarymlm_service_charge" id="binarymlm_service_charge" size="10" value="<?php echo $service_charge ?>"></div>
    <div class="col-md-3"><select name="binarymlm_service_charge_type" id="binarymlm_service_charge_type" >
                                <option value="">Select type</option>
                                <option value="Fixed" <?php echo $service_charge_type == 'Fixed' ? 'selected="selected"' : '' ?>>Fixed</option>
                                <option value="Percentage" <?php echo $service_charge_type == 'Percentage' ? 'selected="selected"' : '' ?>>Percentage</option>
                            </select> 
    <div class="toggle-visibility" id="binarymlm-admin-service-charge"><?php _e('Please specify service charge.', 'mlm'); ?></div></div>
      </div>
    </div>
    </div></div>

    <div class="row">
    <div class="col-md-6">
    <div class="form-group">
      <label> <a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm-admin-tds');"><?php _e('Tax Deduction', 'mlm'); ?> :</a> </label>      
      <div class="row">
        <div class="col-md-3"><input type="text" name="binarymlm_tds" id="binarymlm_tds" size="10" value="<?php echo $tds ?>"></div>
        <div class="col-md-3">%</div>
      </div>
    </div>
    </div>

    <div class="col-md-6">
    <div class="form-group">
      <label> <a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm-admin-cap_limit');"><?php _e('Cap Limit Amount', 'mlm'); ?> :</a></label>
      <input class="form-control" type="text" name="binarymlm_cap_limit_amount" id="binarymlm_cap_limit_amount" size="10" value="<?php echo $cap_limit_amount ?>">
       <div class="toggle-visibility" id="binarymlm-admin-cap_limit"><?php _e('Please specify Cap Limit Amount.', 'mlm'); ?></div>
    </div>
    </div></div>
    
    <div class="col-md-12">
      <div class="row">
        <div class="form-group">
        <button type="submit" name="binarymlm_payout_settings" id="binarymlm_payout_settings" class="btn btn-primary btn-default" onclick="needToConfirm = false;">Submit &raquo;</button>
        </div>
      </div>
    </div>

</div>
</form></div>
            <script language="JavaScript">
                populateArrays();
            </script>

        </div>
        <?php
    } // end if statement
    else {
        echo $msg;
        echo "<script>window.location='$url'</script>";
    }
}//end mlmPayout function
}//end class