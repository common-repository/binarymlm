<?php
defined( 'ABSPATH' ) || exit;

class Letscms_MLM_Registration {
use letscms_mlmfunction;
    public function __construct()
    {
      add_action( 'wp_enqueue_scripts', array($this,'mlm_enqueue_script') );
      add_action( 'wp_ajax_binarymlm_username', array($this,'checkUserName_ajax') );
      add_action( 'wp_ajax_nopriv_binarymlm_username', array($this,'checkUserName_ajax') );
      add_action( 'wp_ajax_binarymlm_sponsor', array($this,'checkUserName_ajax') );
      add_action( 'wp_ajax_nopriv_binarymlm_sponsor', array($this,'checkUserName_ajax') );

    }
    public function mlm_enqueue_script() {
      wp_enqueue_script('mlm-custom-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), false, true);
      wp_enqueue_style('mlm-css', MLM_URL . '/css/mlm.css');
      wp_enqueue_style('mlm-bootstrap-css', MLM_URL . '/css/bootstrap.css');
      wp_enqueue_script('mlm-form-validation', MLM_URL . '/includes/js/form-validation.js',array(),false,true);
      wp_enqueue_script('mlm-ajax', MLM_URL . '/includes/js/ajax.js',array(),false,true);
      wp_enqueue_script('jquery', 'https://www.google.com/jsapi', '', '', true);
      
    }

function checkUserName_ajax(){
  $action = $_REQUEST['action'];
  global $wpdb;
    if($action == 'username')
    {
      $q = $_GET['q'];
      $uname = $wpdb->get_var("SELECT username FROM {$wpdb->prefix}binarymlm_users WHERE username = '$q'");
        if($uname)
          echo "<span class='errormsg'>Sorry! The specified username is not available for registration.</span>";
        else
          echo "<span class='msg'>Congratulations! The username is available.</span>";
    }
    else if($action == 'sponsor')
    {
      $q = $_GET['q'];
      $sname = $wpdb->get_var("SELECT username FROM {$wpdb->prefix}binarymlm_users WHERE `username` = '$q'");
        if(!$sname)
          echo "<span class='errormsg'> Sorry! The specified sponsor is not available for registration.</span>";
        else
          echo "<span class='msg'>Congratulations! Your sponsor is <b> ".ucwords(strtolower($sname))."</b> .</span>";
    }
    die();
}

public function letscms_register_user(){
    global $wpdb, $current_user;
    $error = array();
    $chk = 'error';
    $adminajax= "'".admin_url('admin-ajax.php')."'";
    $mlm_general_settings = get_option('letscms_mlm_general_settings');
    if(is_user_logged_in())
      {
      $sponsor_name = $current_user->user_login;
      $readonly_sponsor = 'readonly';
      }else if(isset($_REQUEST['sp']) &&  $_REQUEST['sp'] != '')
      {
      $sql="SELECT user_key from  `".$wpdb->prefix."mlm_users` WHERE username='".$_REQUEST['sp']."'";
      $check_user_key=$wpdb->get_var($sql);
      $sponsorName = $this->letscms_getusernamebykey($check_user_key);
      if(isset($sponsorName) && $sponsorName !='' )
      {
      $readonly_sponsor = 'readonly';
      $sponsor_name = $sponsorName;
      }else{
      redirectPage(home_url(), array()); exit;
      }
      }else{
        $readonly_sponsor = '';
      }

      //most outer if condition
      if(isset($_POST['submit']))
      { 
        /*Use Sanitization*/
        $firstname = sanitize_text_field( $_POST['firstname'] );
        $lastname = sanitize_text_field( $_POST['lastname'] );
        $username = sanitize_text_field( $_POST['username'] );
        $epin = sanitize_text_field(isset($_POST['epin']) ? $_POST['epin'] : ''); 
        $password = sanitize_text_field( $_POST['password'] );
        $confirm_pass = sanitize_text_field( $_POST['confirm_password'] );
        $email = sanitize_text_field( $_POST['email'] );
        $confirm_email = sanitize_text_field( $_POST['confirm_email'] );
        $address1 = sanitize_text_field( $_POST['address1'] );
        $address2 = sanitize_text_field( $_POST['address2'] );
        $sponsor = sanitize_text_field( $_POST['sponsor'] );
        $city = sanitize_text_field( $_POST['city'] );
        $state = sanitize_text_field( $_POST['state'] );
        $postalcode = sanitize_text_field( $_POST['postalcode'] );
        $telephone = sanitize_text_field( $_POST['telephone'] );
        $dob = sanitize_text_field( $_POST['dob'] );

        /*Use Validation*/
        //Add usernames we don't want used
        $invalid_usernames = array( 'admin' );
        //Do username validation
        $username = sanitize_user( $username );

        if(!validate_username($username) || in_array($username, $invalid_usernames))
          $error[] = __("Username is invalid.",'mlm');

        if ( username_exists( $username ) )
          $error[] = __("Username already exists.",'mlm');

        if (!empty($epin) && $this->letscms_epin_exists($epin)) {
            $error[] = __("ePin already issued or wrong ePin.",'mlm');
        }
        if (!empty($mlm_general_settings['binarymlm_sol_payment']) && empty($epin)) {
            $error[] = __("Please enter your ePin.",'mlm');
        }
       
        if ( $this->letscms_checkInputField($password) )
          $error[] = __("Please enter your password.",'mlm');

        if ( $this->letscms_confirmPassword($password, $confirm_pass) )
          $error[] = __("Please confirm your password.",'mlm');

        if ( $this->letscms_checkInputField($sponsor) )
          $error[] = __("Please enter your sponsor name.",'mlm');

        if ( $this->letscms_checkInputField($firstname) )
          $error[] = __("Please enter your first name.",'mlm');

        if ( $this->letscms_checkInputField($lastname) )
          $error[] = __("Please enter your last name.",'mlm');

        if ( $this->letscms_checkInputField($address1) )
          $error[] = __("Please enter your address.",'mlm');

        if ( $this->letscms_checkInputField($city) )
          $error[] = __("Please enter your city.",'mlm');

        if ( $this->letscms_checkInputField($state) )
          $error[] = __("Please enter your state.",'mlm');

        if ( $this->letscms_checkInputField($postalcode) )
          $error[] = __("Please enter your postal code.",'mlm');

        if ( $this->letscms_checkInputField($telephone) )
          $error[] = __("Please enter your contact number.",'mlm');

        if ( $this->letscms_checkInputField($dob) )
          $error[] = __("Please enter your date of birth.",'mlm');

        //Do e-mail address validation
        if ( !is_email( $email ) )
          $error[] = __("E-mail address is invalid.",'mlm');

        if (email_exists($email))
          $error[] = __("E-mail address is already in use.",'mlm');

        if ( $this->letscms_confirmEmail($email, $confirm_email) )
          $error[] = __("Please confirm your email address.",'mlm');


        $sql = "SELECT COUNT(*) num, `user_key` FROM {$wpdb->prefix}binarymlm_users WHERE `username` = '".$sponsor."'";
        $intro = $wpdb->get_row($sql);

        if(isset($_GET['l']) && $_GET['l']!=''){
        $leg = $_GET['l']; }
        else{
        $leg = $_POST['leg'];
        }
        if($leg!='0')
        {
          if($leg!='1')
          {
          $error[] = __("You have enter a wrong placement.",'mlm');
          }
        }

        //generate random numeric key for new user registration
        $user_key = $this->letscms_generateKey();
        $threedigitkey=$this->letscms_generateThreeDigitKey();

        // outer if condition for checking if error is empty
        if(empty($error))
        { 
        // inner if condition
          if($intro->num==1)
          {
          $sponsor = $intro->user_key;
          $sponsor1 = $sponsor;
            //find parent key
            if(!empty($_GET['k']) && $_GET['k']!='')
              {
              $parent_key = $_GET['k'];
              } else {
              $readonly_sponsor = '';
            do
            {
            $sql = "SELECT `user_key` FROM {$wpdb->prefix}binarymlm_users WHERE parent_key = '".$sponsor1."' AND leg = '".$leg."' AND banned = '0'";
            $spon = $wpdb->get_var($sql);
            $num = $wpdb->num_rows;
            if($num)
                { $sponsor1 = $spon; }
            }while($num==1);
            $parent_key = $sponsor1;
            }
          $user = array
          (
            'user_login' => $username,
            'user_pass' => $password,
            'first_name' => $firstname,
            'last_name' => $lastname,
            'user_email' => $email
          );

          // return the wp_users table inserted user's ID
          $user_id = wp_insert_user($user);
          $user = new WP_User( $user_id );
          $user->set_role( 'binarymlm_user' );

          //get the selected country name from the country table
          $country = $_POST['country'];
          $sql = "SELECT name FROM {$wpdb->prefix}binarymlm_country WHERE id = '".$country."'";
          $country1 = $wpdb->get_var($sql);

          //insert the registration form data into user_meta table
          add_user_meta( $user_id, 'user_address1', $address1 );
          add_user_meta( $user_id, 'user_address2', $address2 );
          add_user_meta( $user_id, 'user_city', $city );
          add_user_meta( $user_id, 'user_state', $state );
          add_user_meta( $user_id, 'user_country', $country1 );
          add_user_meta( $user_id, 'user_postalcode', $postalcode );
          add_user_meta( $user_id, 'user_telephone', $telephone );
          add_user_meta( $user_id, 'user_dob', $dob );

          /*Send e-mail to admin and new user -
            You could create your own e-mail instead of using this function*/
            wp_new_user_notification($user_id, $password);

        // code to send three digit key to the new registered user

            $message="Dear $username \n Please Find Your three digit security key  $threedigitkey";
            wp_mail( $email, 'Three Digit Key', $message);

            $pc = isset($mlm_general_settings['binarymlm_product_price']) ? $mlm_general_settings['binarymlm_product_price'] : '0';

                //insert the data into fa_user table
                if (!empty($epin)) {
                    $pointResult = $wpdb->get_row("select p_id,point_status from {$wpdb->prefix}binarymlm_epins where epin_no = '{$epin}'");
                    $pointStatus = $pointResult->point_status;
                    $productPrice = $wpdb->get_var("SELECT product_price FROM {$wpdb->prefix}binarymlm_product_price WHERE p_id = '" . $pointResult->p_id . "'");
                    // to epin point status 1 
                    if ($pointStatus[0] == '1') {
                        $paymentStatus = '1';
                        $payment_date = current_time('mysql');
                    }
                    // to epin point status 1 
                    else if ($pointStatus[0] == '0') {
                        $paymentStatus = '2';
                        $payment_date = current_time('mysql');
                    }
                }

          //insert the data into wp_mlm_user table
          $insert = "INSERT INTO {$wpdb->prefix}binarymlm_users (user_id, username, user_key, parent_key, sponsor_key, leg,security_key,payment_date,payment_status,product_price)
          VALUES('".$user_id."','".$username."', '".$user_key."', '".$parent_key."', '".$sponsor."', '".$leg."','".$threedigitkey."','".$payment_date."','".$paymentStatus."','".$productPrice."')";

          //begin most inner if condition to check if all data successfully inserted
          if($wpdb->query($insert))
          {
            //entry on Left and Right Leg tables
            if($leg==0)
            {
              $insert = "INSERT INTO {$wpdb->prefix}binarymlm_leftleg (pkey, ukey) VALUES ('".$parent_key."','".$user_key."')";
              $insert = $wpdb->query($insert);
            }
            else if($leg==1)
            {
              $insert = "INSERT INTO {$wpdb->prefix}binarymlm_rightleg(pkey, ukey) VALUES('".$parent_key."','".$user_key."')";
              $insert = $wpdb->query($insert);
            }
            //begin while loop
          while($parent_key!='0')
          {
            $query = "SELECT COUNT(*) num, parent_key, leg FROM {$wpdb->prefix}binarymlm_users WHERE user_key = '".$parent_key."' AND banned = '0'";
            $result = $wpdb->get_row($query);
            if($result->num==1)
            {
              if($result->parent_key!='0')
              {
                if($result->leg==1)
                {
                  $tbright = "INSERT INTO {$wpdb->prefix}binarymlm_rightleg(pkey,ukey) VALUES('".$result->parent_key."','".$user_key."')";
                  $tbright = $wpdb->query($tbright);
                }
                else
                {
                  $tbleft = "INSERT INTO {$wpdb->prefix}binarymlm_leftleg(pkey,ukey) VALUES('".$result->parent_key."','".$user_key."')";
                  $tbleft = $wpdb->query($tbleft);
                }
              }
              $parent_key = $result->parent_key;
            }
            else
            {
              $parent_key = '0';
            }
          }//end while loop
          if (isset($epin) && !empty($epin)) {
                        $sql = "update {$wpdb->prefix}binarymlm_epins set user_key='{$user_key}', date_used='" . current_time('mysql') . "', status=1 where epin_no ='{$epin}' ";
                        $wpdb->query($sql);
                        }
                    if ($paymentStatus == 1) {
                           $this-> insert_refferal_commision($user_id);
                        }

           $chk = '';
$mlm_settings = get_option('letscms_mlm_general_settings');
if(!empty($mlm_settings['binarymlm_reg_url'])){
$reg_url = $mlm_settings['binarymlm_reg_url']; 
$url = get_bloginfo('url'). "/" .$reg_url;
echo "<script>window.location='$url'</script>";
}else{
  $msg = "<div class='notibar msgsuccess'><p>Welcome to our MLM! You are successfully registered.</p></div>";
}

}//end of most inner if condition

} //end of inner if condition
else{
  $error[] =   __("Sponsor does not exist in the system",'mlm');
}

}//end of outer if condition
}//end of most outer if condition

if(!empty($error)){ 
  foreach($error as $er){
             echo $er."</br>";
            }
}

if($chk!='')
  {

        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);
        $general_setting = get_option('letscms_mlm_general_settings');
        if (is_user_logged_in()) {
            if (!empty($general_setting['binarymlm_wp_reg']) && !empty($general_setting['binarymlm_reg_url']) && $user_role != 'binarymlm_user') {
                echo "<script>window.location ='" . site_url() . '/' . $general_setting['binarymlm_reg_url'] . "'</script>";
            }
        }
        else {
            if (!empty($general_setting['binarymlm_wp_reg']) && !empty($general_setting['binarymlm_reg_url'])) {
                echo "<script>window.location ='" . site_url() . '/' . $general_setting['binarymlm_reg_url'] . "'</script>";
            }
        }
    ?>

    <form name="frm" method="post" action="" onSubmit="return formValidation();">
      <div class="col-md-12">
        <div class="form-group">
          <label>Create Username <span style="color:red;">*</span> :</label>
          <input type="text" class="form-control" name="username" id="letscms_username" maxlength="20" size="37" value="<?php isset($_POST['username'])?htmlentities($_POST['username']):''?>" onBlur="checkUserNameAvailability(<?php echo $adminajax;?>,this.value);">
          <br /><div id="check_user"></div>
        </div>
      </div>
 <?php
  $mlm_general_settings = get_option('letscms_mlm_general_settings');
  $adminajax= "'".admin_url('admin-ajax.php')."'";
  if (!empty($mlm_general_settings['binarymlm_ePin_activate']) && !empty($mlm_general_settings['binarymlm_sol_payment'])) {
?>
<div class="col-md-12">
        <div class="form-group">
          <label><?php _e('Enter ePin', 'mlm'); ?><span style="color:red;">*</span> :</label>
          <input type="text" name="epin" class="form-control"
          id="letscms_epin" value="<?php if (!empty($_POST['epin'])) _e(htmlentities($_POST['epin'])); ?>" maxlength="20" size="37" onBlur="checkePinAvailability(<?php echo $adminajax;?>,this.value);">
          <br /><div id="check_epin"></div>
        </div>
      </div>
<?php } else if (!empty($mlm_general_settings['binarymlm_ePin_activate'])) { ?>
<div class="col-md-12">
        <div class="form-group">
          <label><?php _e('Enter ePin', 'mlm'); ?><span style="color:red;">*</span> :</label>
          <input type="text" name="epin" class="form-control"
          id="letscms_epin" value="<?php if (!empty($_POST['epin'])) _e(htmlentities($_POST['epin'])); ?>" maxlength="20" size="37" onBlur="checkePinAvailability(<?php echo $adminajax;?>,this.value);">
          <br /><div id="check_epin"></div>
        </div>
      </div>
 <?php
                 } ?>
<div class="col-md-12">
        <div class="form-group">
          <label><?php _e("Create Password",'mlm') ?> <span style="color:red;">*</span></label>
          <input class="form-control" type="password" name="password" id="letscms_password" maxlength="20" size="37" >
          <span style="font-size:12px; font-style:italic; color:#666666">Password length at least 6 character</span>
          
        </div>
      </div>
<div class="col-md-12">
        <div class="form-group">    
 <label><?php _e("Confirm Password",'mlm') ?> <span style="color:red;">*</span></label>
<input class="form-control" type="password" name="confirm_password" id="letscms_confirm_password" maxlength="20" size="37" >
</div>
</div>
<?php
if(isset($sponsor_name) && $sponsor_name!='')
{
  $spon = $sponsor_name;
}
else if(isset($_POST['sponsor']))
  $spon = htmlentities($_POST['sponsor']);
?>
<div class="col-md-12">
  <div class="form-group">
    <label><div><?php _e("Sponsor Name",'mlm') ?> <span style="color:red;">*</span> :</div></label>
      <input class="form-control" type="text" name="sponsor" id="letscms_sponsor" value="<?= isset($spon)?$spon:''?>" maxlength="20" size="37" onBlur="checkReferrerAvailability(<?php echo $adminajax;?>,this.value);" <?= $readonly_sponsor;?>>
      <div id="check_referrer"></div>
      </div>
    </div>
<?php
  if(isset($_POST['leg']) && $_POST['leg']=='0')
  $checked = 'checked';
  else if(isset($_GET['l']) && $_GET['l']=='0')
  {
  $checked = 'checked';
  $disable_leg = 'disabled';
  }
  else
  $checked = '';
  if(!empty($_POST['leg']) && $_POST['leg']=='1')
  $checked1 = 'checked';
  else if(!empty($_GET['l']) && $_GET['l']=='1')
  {
  $checked1 = 'checked';
  $disable_leg = 'disabled';
  }
  else
  $checked1 = '';
?>
<div class="col-md-12">
    <div class="form-group">
    <div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("Placement",'mlm') ?> <span style="color:red;">*</span> :</label></div>
       <?php _e("Left",'mlm') ?> &nbsp;&nbsp;&nbsp;&nbsp;<input id="letscms_left" type="radio" name="leg" value="0" <?= $checked;?> <?php echo isset($disable_leg)?$disable_leg:'';?>/>&nbsp;&nbsp;&nbsp;&nbsp;
      <?php _e("Right",'mlm') ?>&nbsp;&nbsp;&nbsp;&nbsp;<input  id="letscms_right" type="radio" name="leg" value="1" <?= $checked1;?> <?php echo isset($disable_leg)?$disable_leg:'';?>/>
    </div>
  </div>
  <div class="col-md-12">
  <div class="form-group">
<div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("First Name",'mlm') ?> <span style="color:red;">*</span> :</label></div>
    <input class="form-control" type="text" name="firstname" id="letscms_firstname" value="<?= isset($_POST['firstname'])?htmlentities($_POST['firstname']):''?>" maxlength="20" size="37" onBlur="return checkname(this.value, 'firstname');" >
    <div id="check_firstname"></div>
  </div>
</div>
      <div class="col-md-12">
        <div class="form-group">
          <div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("Last Name",'mlm') ?> <span style="color:red;">*</span> :</label></div>
          <input class="form-control" type="text" name="lastname" id="letscms_lastname" value="<?= isset($_POST['lastname'])?htmlentities($_POST['lastname']):''?>" maxlength="20" size="37" onBlur="return checkname(this.value, 'lastname');">
          <div id="check_lastname"></div>
          </div>
        </div>
         <div class="col-md-12">
        <div class="form-group">

         <div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("Address Line 1",'mlm') ?> <span style="color:red;">*</span> :</label></div>
          <input class="form-control" type="text" name="address1" id="letscms_address1" value="<?= isset($_POST['address1'])?htmlentities($_POST['address1']):''?>"  size="37" >
         </div>
       </div>
        <div class="col-md-12">
        <div class="form-group">
       <div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("Address Line 2",'mlm') ?> :</label></div>
       <input class="form-control" type="text" name="address2" id="letscms_address2" value="<?= isset($_POST['address2'])?htmlentities($_POST['address2']):''?>"  size="37" >
         </div>
       </div>
        <div class="col-md-12">
        <div class="form-group">
        <div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("City",'mlm') ?> <span style="color:red;">*</span> :</label></div>
        <input class="form-control" type="text" name="city" id="letscms_city" value="<?= isset($_POST['city'])?htmlentities($_POST['city']):''?>" maxlength="30" size="37" >
         </div>
       </div>
        <div class="col-md-12">
        <div class="form-group">
       <div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("State",'mlm') ?> <span style="color:red;">*</span> :</label></div>
       <input class="form-control" type="text" name="state" id="letscms_state" value="<?= isset($_POST['state'])?htmlentities($_POST['state']):''?>" maxlength="30" size="37" >
         </div>
       </div>
         <div class="col-md-12">
        <div class="form-group">
         <div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("Postal Code",'mlm') ?> <span style="color:red;">*</span> :</label></div>
         <input class="form-control" type="text" name="postalcode" id="letscms_postalcode" value="<?= isset($_POST['postalcode'])?htmlentities($_POST['postalcode']):''?>" maxlength="20" size="37" onBlur="return allowspace(this.value,'postalcode');">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
        <div style="margin-right:10px;margin-bottom: 10px;"><label>
          Country <span style="color:red;">*</span> :</label></div>
          <?php $sql = "SELECT id, name FROM {$wpdb->prefix}binarymlm_country ORDER BY name";
          $results = $wpdb->get_results($sql); ?>
          <select  name="country" id="letscms_country" class="form-control" >
          <option value=""><?php _e("Select Country",'mlm') ?></option>
          <?php
          foreach($results as $row)
          {
          if($_POST['country']==$row->id)
          $selected = 'selected';
          else
          $selected = '';
          ?>
          <option value="<?= $row->id;?>" <?= $selected?>><?= $row->name;?></option>
          <?php } ?></select>
        </div>
      </div>
       <div class="col-md-12">
        <div class="form-group">
        <div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("Email Address",'mlm') ?> <span style="color:red;">*</span> :</label></div>
        <input class="form-control" type="text" name="email" id="letscms_email" value="<?= isset($_POST['email'])?htmlentities($_POST['email']):''?>"  size="37" >
      </div>
    </div>
     <div class="col-md-12">
        <div class="form-group">
    <div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("Confirm Email Address",'mlm') ?> <span style="color:red;">*</span> :</label></div>
    <input class="form-control" type="text" name="confirm_email" id="letscms_confirm_email" value="<?= isset($_POST['confirm_email'])?htmlentities($_POST['confirm_email']):''?>" size="37" >
    </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
          <div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("Contact No.",'mlm') ?> <span style="color:red;">*</span> :</label></div>
          <input class="form-control" type="text" name="telephone" id="letscms_telephone" value="<?= isset($_POST['telephone'])?htmlentities($_POST['telephone']):''?>" maxlength="20" size="37" onBlur="return numeric(this.value, 'telephone');" >
          <div id="numeric_telephone"></div>
         </div>
       </div>
        <div class="col-md-12">
        <div class="form-group">
        <div style="margin-right:10px;margin-bottom: 10px;"><label><?php _e("Date of Birth",'mlm') ?> <span style="color:red;">*</span> :</label></div>
        <input class="form-control" type="text" name="dob" id="letscms_dob" value="<?= isset($_POST['dob'])?htmlentities($_POST['dob']):''?>" maxlength="20" size="22" >
      </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
    <input style="padding: 7px 30px 7px 30px;
    margin-left: 200px;
    background: #050505;
    border: 0px;
    border-radius: 5px;
    color: #ffffff;
    font-weight: bold;" type="submit" name="submit" id="submit" value="Submit" />
  </div>
</div>
</form>
    

<?php
}//end of chk='' condition
else{
  echo $msg;
}
}//end of function letscms_register_user
}//end of class
