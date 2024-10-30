<?php
defined( 'ABSPATH' ) || exit;
class Letscms_MLM_General_Settings{
  use letscms_mlmfunction;
  public function mlmGeneral(){
    global $wpdb;
    $error = array();
    $chk = 'error';
    $this->letscms_check_first_user();
    if(isset($_POST['binarymlm_general_settings']))
    {
     $currency = sanitize_text_field( $_POST['binarymlm_currency'] );
     $letscms_checkInputField=$this->letscms_checkInputField($currency);
     if ( $letscms_checkInputField ){
      $error[] = __("Please Select your currency type.",'mlm');
     }

     $reg_url = sanitize_text_field($_POST['binarymlm_reg_url']);

     if (isset($_POST['binarymlm_wp_reg']) && empty($reg_url))
     {
       $error[] = __("Please Fill The URL.",'mlm');
     }

     //if any error occoured
     if(empty($error)){
       $chk = '';
       update_option('letscms_mlm_general_settings', $_POST);

       /*********code to save the product price that have payment status 1 and product price 0 **********/
       $mlm_general_settings = get_option('letscms_mlm_general_settings');
            if (!empty($mlm_general_settings['binarymlm_product_price'])) {
                $product_price = $mlm_general_settings['binarymlm_product_price'];
                $sql = "SELECT id FROM {$wpdb->prefix}binarymlm_users WHERE payment_status='1' AND product_price='0'";
                $ids = $wpdb->get_results($sql);
                foreach ($ids as $id) {
                    $sql = "update {$wpdb->prefix}binarymlm_users set product_price='{$product_price}' where id ='{$id->id}' ";

                    $wpdb->query($sql);
                }
            }

       $url = get_bloginfo('url')."/wp-admin/admin.php?page=binarymlm-admin-settings&tab=eligibility";
       $msg = "<div class='notibar msgsuccess'><p>Your general settings have been successfully updated.</p></div>";
      
     }
   }// end outer if condition


   if($chk!='')
   {
     $mlm_settings = get_option('letscms_mlm_general_settings');
     $URL = empty($mlm_settings['binarymlm_affiliate_url']) ? '' : $mlm_settings['binarymlm_affiliate_url'] . '/';
    ?>
      <div class='wrap1'>

      <?php if($error) {?>
      <div class="notibar msgerror">
          <p> <strong><?php _e('Please Correct the following Error :','mlm'); ?></strong> 
          <?php foreach($error as $err){
               echo $err."</br>"; }
          ?></p>
      </div>
      <?php } ?>


      <?php
            $currency = (isset($_POST['binarymlm_currency']) ? $_POST['binarymlm_currency'] : (isset($mlm_settings['binarymlm_currency']) ? $mlm_settings['binarymlm_currency'] : ''));
            $wp_reg = (isset($_POST['binarymlm_wp_reg']) ? $_POST['binarymlm_wp_reg'] : (isset($mlm_settings['binarymlm_wp_reg']) ? $mlm_settings['binarymlm_wp_reg'] : ''));
            $reg_url = (isset($_POST['binarymlm_reg_url']) ? $_POST['binarymlm_reg_url'] : (isset($mlm_settings['binarymlm_reg_url']) ? $mlm_settings['binarymlm_reg_url'] : ''));
            $affiliate_url = (isset($_POST['binarymlm_affiliate_url']) ? $_POST['binarymlm_affiliate_url'] : (isset($mlm_settings['binarymlm_affiliate_url']) ? $mlm_settings['binarymlm_affiliate_url'] : ''));
            $product_price = (isset($_POST['binarymlm_product_price']) ? $_POST['binarymlm_product_price'] : (isset($mlm_settings['binarymlm_product_price']) ? $mlm_settings['binarymlm_product_price'] : ''));
            $process_withdrawal = (isset($_POST['binarymlm_process_withdrawal']) ? $_POST['binarymlm_process_withdrawal'] : (isset($mlm_settings['binarymlm_process_withdrawal']) ? $mlm_settings['binarymlm_process_withdrawal'] : ''));
            if (empty($process_withdrawal))
        $_POST['binarymlm_process_withdrawal'] = 'Manually';
            ?>
<div class="container1" >
<div class="box1" ><h4 align="center">Binary MLM General Setting</h4></div>
<div class="wrap2">
<form role="form" name="binarymlm_admin_general_settings" method="post" action="" id="binarymlm_admin_general_settings">
  <div class="col-md-12 row">
    <div class="col-md-6">
    <div class="form-group">
      <label for="binarymlm_currency"><a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('binarymlm-admin-currency');"><strong><?php _e('Currency', 'mlm'); ?></strong> <span style="color:red;">*</span>: </a> </label>
          <?php
          $sql = "SELECT iso3, currency FROM {$wpdb->prefix}binarymlm_currency ORDER BY iso3";
          $results = $wpdb->get_results($sql);
          ?>
          <select class="form-control"name="binarymlm_currency" id="binarymlm_currency" >
              <option value=""><?php _e('Select Currency', 'mlm'); ?></option>
              <?php
              foreach ($results as $row) {
                  if ($currency == $row->iso3)
                      $selected = 'selected';
                  else
                      $selected = '';
                  ?>
                  <option value="<?php echo $row->iso3; ?>" <?php echo $selected ?>><?php echo $row->iso3 . " - " . $row->currency; ?></option>
                  <?php
          }
          ?>
          </select>
          <div class="toggle-visibility" id="binarymlm-admin-currency"><?php _e('Select your currency which will you use.', 'mlm'); ?></div>
    </div>
    </div>
     <div class="col-md-6">
    <div class="form-group">
      <label for=""> <strong><?php _e('URL of registration page', 'mlm'); ?><span style="color:red;"></span>:</strong></label>
      <?php echo site_url() . '/' ?><input class="form-control" type="text" name="binarymlm_reg_url" id="binarymlm_reg_url" value="<?php echo $reg_url ?>" readonly="true"/>
    </div>
    </div>
  </div>
    <div class="col-md-12"><hr></div>
    
    <div class="col-md-12 row">
      <div class="col-md-6">
        <div class="form-group">
        <label for=""> <strong><?php _e('Redirect Affiliate URL', 'mlm'); ?>:</strong></label>
        <?php echo site_url() . '/' ?><input class="form-control" type="text" name="binarymlm_affiliate_url" id="binarymlm_affiliate_url" value="<?php echo $affiliate_url ?>" />
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
        <label for=""> <strong><?php _e('ePin Length', 'mlm'); ?></strong></label>

       <?php $epin_length = $mlm_settings['binarymlm_epin_length'] ?>
                <select class="form-control" name="binarymlm_epin_length" id="binarymlm_epin_length">
                    <?php
                    // or whatever you want
                    $epin_array = array(8 => '8', 9 => '9', 10 => '10', 11 => '11', 12 => '12', 13 => '13', 14 => '14', 15 => '15');
                    foreach ($epin_array as $key => $val) {
                        ?>
                        <option value="<?php echo $key; ?>"<?php if ($key == $epin_length) echo ' selected="selected"'; ?>>
                            <?php echo $val; ?>
                        </option>
                        <?php
                    }
                    ?>

                </select>
        </div>
      </div>
    </div>

  <div class="col-md-12"><hr></div>
  <div class="col-md-12 row">
    <div class="col-md-6">
    <div class="form-group">
      <label for=""> <strong><?php _e('Use WP registration page'); ?></strong> </label>
      <input class="form-control" type="checkbox" name="binarymlm_wp_reg" id="binarymlm_wp_reg" value="1" <?php echo ($wp_reg == 1) ? ' checked="checked"' : ''; ?> onclick="CheckBoxChanged(this);" onblur="show1();" />
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
      <label for=""><strong><?php _e('Activate ePin', 'mlm'); ?> :</strong> </label>
      <input class="radio" type="radio" name="binarymlm_ePin_activate" value="1" <?php
                if (isset($mlm_settings['binarymlm_ePin_activate']) && $mlm_settings['binarymlm_ePin_activate'] == '1') { echo 'checked';}?> /> <?php _e('Yes', 'mlm'); ?>
         <?php global $wpdb;
         $sql = "SELECT COUNT( * ) AS ps FROM  {$wpdb->prefix}binarymlm_users WHERE  `payment_status` =  '2'";
         $ps = $wpdb->get_var($sql);
         $sql1 = "SELECT COUNT( * ) AS es FROM  {$wpdb->prefix}binarymlm_epins WHERE  `status` =  '1'";
         $es = $wpdb->get_var($sql1);
         if ($ps > 0 || $es > 0) { echo "<br>" . __('Cannot be disabled as 1 or more ePins have been used for registration.', 'mlm');
         } else {?>
          <input class="radio" type="radio" name="binarymlm_ePin_activate" value="0" <?php
            if (isset($mlm_settings['binarymlm_ePin_activate']) && $mlm_settings['binarymlm_ePin_activate'] == '0') {
            echo 'checked';
        }
      ?> /> <?php _e('No', 'mlm'); ?>
         <?php } ?>
    </div>
    </div>
   </div>

    
    <div class="col-md-12"><hr></div>

    <div class="col-md-12 row">
    <div class="col-md-6">
    <div class="form-group">
      <label for=""><strong><?php _e('Product Price', 'mlm'); ?> :</strong> </label><br>
      <input class="radio" type="radio" name="binarymlm_product_price" value="1" <?php
                if (isset($mlm_settings['binarymlm_product_price']) && $mlm_settings['binarymlm_product_price'] == '1') {
                    echo 'checked';
                }
                ?>/> <?php _e('Yes', 'mlm'); ?>
                      
                    <input class="radio" type="radio" name="binarymlm_product_price" value="0" <?php
                    if (isset($mlm_settings['binarymlm_product_price']) && $mlm_settings['binarymlm_product_price'] == '0') {
                        echo 'checked';
                    }
                    ?>/> <?php _e('No', 'mlm'); ?>
                       <?php  ?>
    </div>
    </div>

    <div class="col-md-6">
    <div class="form-group">
      <label for=""> <strong><?php _e('Sole Payment Method', 'mlm'); ?>:</strong></label>
      <div class="form-group">
      <input class="radio" type="radio" name="binarymlm_sol_payment" value="1" <?php
                if (isset($mlm_settings['binarymlm_sol_payment']) && $mlm_settings['binarymlm_sol_payment'] == '1') {
                    echo 'checked';
                }
                ?>/> <?php _e('Yes', 'mlm'); ?>
                <input class="radio" type="radio" name="binarymlm_sol_payment" value="0" <?php
                if (isset($mlm_settings['binarymlm_sol_payment']) && $mlm_settings['binarymlm_sol_payment'] == '0') {
                    echo 'checked';
                }
                ?>/> <?php _e('No', 'mlm'); ?>
    </div></div>
    </div>
</div>
    <div class="col-md-12"><hr></div>
<div class="col-md-12 row">
    <div class="col-md-6">
    <div class="form-group">
      <label for="">  <strong><?php _e('Process Withdrawals', 'mlm'); ?> :</strong></label>
      <div class="form-group">
      <input type="radio" name="binarymlm_process_withdrawal" id="binarymlm_process_withdrawal" value="Automatically" <?php echo ($process_withdrawal == 'Automatically') ? ' checked="checked"' : '' ?> />Automatically
      <input type="radio" name="binarymlm_process_withdrawal" id="binarymlm_process_withdrawal" value="Manually" <?php echo ( $process_withdrawal == 'Manually') ? ' checked="checked"' : '' ?> />Manually
    </div></div>
    </div>

    <div class="col-md-6">
    <div class="form-group">
      <button type="submit" name="binarymlm_general_settings" id="binarymlm_general_settings" class="btn btn-primary btn-default" onclick="needToConfirm = false;">Submit &raquo;</button>
    </div>
    </div>
   </div>
  </div>
</form></div>
</div>

        <script language="JavaScript">
            populateArrays();
        </script>
 <?php
    } // end if statement
    else{
        echo $msg;
        echo "<script>window.location='$url'</script>";
      }
}//end mlmGeneral function
}
