<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Eligibility_Settings{
  use letscms_mlmfunction;
  /***********************************Eligibility Settings***********************************/
 public function mlmEligibility(){
   $error = array();
   $chk = 'error';
   $this->letscms_check_first_user();
   //most outer if condition
   if(isset($_POST['binarymlm_eligibility_settings']))
   {
     $direct_referral = sanitize_text_field( $_POST['binarymlm_direct_referral'] );
     $right_referral = sanitize_text_field( $_POST['binarymlm_right_referral'] );
     $left_referral = sanitize_text_field( $_POST['binarymlm_left_referral'] );

     if ( $this->letscms_checkInputField($direct_referral) )
       $error[] = __("Please specify your direct active referrals.",'mlm');
     if ( $this->letscms_checkInputField($right_referral) )
       $error[] = __("Please specify your right leg active referrals.",'mlm');
     if ( $this->letscms_checkInputField($left_referral) )
       $error[] = __("Please specify your left leg active referrals.",'mlm');
     //if any error occoured
   if(empty($error))
     {
        $chk = '';
        update_option('letscms_mlm_eligibility_settings', $_POST);
        $url = get_bloginfo('url')."/wp-admin/admin.php?page=binarymlm-admin-settings&tab=payout";
        $msg = "<div class='notibar msgsuccess'><p>Your eligibility settings have been successfully updated.</p></div>";
     }
   }// end outer if condition

   if($chk!='')
   {
     $mlm_settings = get_option('letscms_mlm_eligibility_settings');
     ?>

   <div class='wrap1'>
   
   <?php if($error) {?>
   <div class="notibar msgerror">
      <p> <strong>Please Correct the following Error :</strong>
       <?php foreach($error as $err){
               echo $err."</br>"; }
       ?></p>
   </div><?php } 

      $direct_referral = (isset($_POST['binarymlm_direct_referral']) ? $_POST['binarymlm_direct_referral'] : (isset($mlm_settings['binarymlm_direct_referral']) ? $mlm_settings['binarymlm_direct_referral'] : ''));
      $left_referral = (isset($_POST['binarymlm_left_referral']) ? $_POST['binarymlm_left_referral'] : (isset($mlm_settings['binarymlm_left_referral']) ? $mlm_settings['binarymlm_left_referral'] : ''));
      $right_referral = (isset($_POST['binarymlm_right_referral']) ? $_POST['binarymlm_right_referral'] : (isset($mlm_settings['binarymlm_right_referral']) ? $mlm_settings['binarymlm_right_referral'] : ''));
?>

  <div class="container1" >
  <div class="box1" ><h4 align="center">Binary MLM Eligibility Settings</h4></div>
    <div class="wrap2">
     <form role="form" name="binarymlm_admin_eligibility_settings" method="post" action="" >
      
        <div class="col-md-12">
        <div class="form-group">
          <label for="direct_referral"><a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm_direct_referral');"><?php _e('Number of direct paid referral(s)', 'mlm'); ?> <span style="color:red;">*</span>: </a></label>
          <input class="form-control" type="text" name="binarymlm_direct_referral" id="binarymlm_direct_referral" size="10" value="<?php echo $direct_referral ?>"><div class="toggle-visibility" id="binarymlm_direct_referral"><?php _e('Please specify direct referral by you.', 'mlm'); ?></div>
        </div>
      </div>
      
      <div class="col-md-12">
      <div class="form-group">
        <label for="right_referral"><a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm_right_referral');"><?php _e('Number of paid referral(s) on right leg', 'mlm'); ?> <span style="color:red;">*</span>: </a></label>
        <input class="form-control" type="text" name="binarymlm_right_referral" id="binarymlm_right_referral" size="10" value="<?php echo $right_referral ?>"><div class="toggle-visibility" id="binarymlm_right_referral"><?php _e('Please specify no. of paid referrals on right leg.', 'mlm'); ?></div>
      </div>
      </div>

      <div class="col-md-12">
      <div class="form-group">
        <label for="left_referral"><a style="cursor:pointer;"title="Click for Help!" onclick="toggleVisibility('binarymlm_left_referral');"><?php _e('Number of paid referral(s) on left leg', 'mlm'); ?> <span style="color:red;">*</span>: </a></label>
        <input class="form-control" type="text" name="binarymlm_left_referral" id="binarymlm_left_referral" size="10" value="<?php echo $left_referral ?>"><div class="toggle-visibility" id="binarymlm_left_referral"><?php _e('Please specify no. of paid referrals on left leg.', 'mlm'); ?></div>
       
      </div>
      </div>

      <div class="col-md-12">
      <div class="form-group">
        <button type="submit" name="binarymlm_eligibility_settings" id="binarymlm_eligibility_settings" class="btn btn-primary btn-default" onclick="needToConfirm = false;">Submit &raquo;</button>
      </div>
      </div>
      
     
    </form>
  </div>

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
}//end mlmEligibility funtion
}//end class