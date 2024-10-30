<?php
defined( 'ABSPATH' ) || exit;
class Letscms_MLM_Register_First_User{
  use letscms_mlmfunction;
  /*******************************First User Register Function***********************************/
  public function register_first_user(){
    global $wpdb;
    $error = array();
    $chk = 'error';

    //most outer if condition
  if(isset($_POST['submit']))
  {
    $username = sanitize_text_field( $_POST['username'] );
    $password = sanitize_text_field( $_POST['password'] );
    $confirm_pass = sanitize_text_field( $_POST['confirm_password'] );
    $email = sanitize_text_field( $_POST['email'] );
    $confirm_email = sanitize_text_field( $_POST['confirm_email'] );

    //Add usernames we don't want used
    $invalid_usernames = array( 'admin' );

    //Do username validation
    $username = sanitize_user( $username );

    if(!validate_username($username) || in_array($username, $invalid_usernames))
      $error[] = __("Username is invalid.",'mlm');

    if ( username_exists( $username ) )
      $error[] = __("Username already exists.",'mlm');

    if ( $this->letscms_checkInputField($username) )
     $error[] = __("Please enter your username.",'mlm');

    if ( $this->letscms_checkInputField($password) )
      $error[] = __("Please enter your password.",'mlm');

    if ( $this->letscms_confirmPassword($password, $confirm_pass) )
      $error[] = __("Please confirm your password.",'mlm');

    //Do e-mail address validation
    if ( !is_email( $email ) )
      $error[] = __("E-mail address is invalid.",'mlm');

    if ( email_exists($email) )
      $error[] = __("E-mail address is already in use.",'mlm');

    if ( $this->letscms_confirmEmail($email, $confirm_email) )
      $error[] = __("Please confirm your email address.",'mlm');

    //generate random numeric key for new user registration
    $user_key = $this->letscms_generateKey();

    // outer if condition
    if(empty($error))
    {
        $user = array
        (
          'user_login' => $username,
          'user_pass' => $password,
          'user_email' => $email
        );

        // return the wp_users table inserted user's ID
        $user_id = wp_insert_user($user);
        $user = new WP_User( $user_id );
        $user->set_role( 'binarymlm_user' );
        /*Send e-mail to admin and new user -
        You could create your own e-mail instead of using this function*/
        wp_new_user_notification($user_id, $password);

        //insert the data into wp_user table
        $insert = "INSERT INTO {$wpdb->prefix}binarymlm_users (user_id, username, user_key, parent_key, sponsor_key, leg)
                          VALUES('".$user_id."','".$username."', '".$user_key."', '0', '0', '0')";

        // if all data successfully inserted
        if($wpdb->query($insert))
        {
          $chk = '';
        }
     }//end outer if condition
     }//end most outer if condition
     if($chk!='')
     {
    ?>
    <div class='wrap'>
   <?php if($error) {?>
   <div class="notibar msgerror">
     <p> <strong><?php _e('Please Correct the following Error(s):','mlm'); ?><br/></strong>
       <?php foreach($error as $er){
         echo $er."</br>";
       } ?></p>
   </div>
  <?php } ?>
 <div class="container1" >
  <div class="box1" ><h4 align="center">Create First User in BinaryMLM</h4></div>
    <div class="wrap2">
    <form name="frm" method="post" action="" onSubmit="return formValidation();">
    <div class="col-md-12">
      
      
        <div class="form-group">
          <label><a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('create-first-user');"><?php _e('Create Username <span style="color:red;">*</span>:');?> </a></label>
          
          <input class="form-control" type="text" name="username" id="letscms_username" size="10" value="<?php isset($_POST['username'])?htmlentities($_POST['username']):''?>"><div class="toggle-visibility" id="create-first-user"><?php _e('Please create the first user of your network.', 'mlm'); ?></div>
        </div>
      

      <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label><a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('email-address');">
            <?php _e('Email Address <span style="color:red;">*</span>:');?> </a></label>

          <input class="form-control" type="text" name="email" id="letscms_email" size="10" value="<?php isset($_POST['email'])?htmlentities($_POST['email']):''?>"><div class="toggle-visibility" id="email-address"><?php _e('Please specify your email address.');?></div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label><a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('confirm-address');">
       <?php _e('Confirm Email Address <span style="color:red;">*</span>:');?> </a></label>

          <input class="form-control" type="text" name="confirm_email" id="letscms_confirm_email" size="10" value="<?php isset($_POST['confirm_email'])?htmlentities($_POST['confirm_email']):''?>"><div class="toggle-visibility" id="confirm-address"><?php _e('Please confirm your email address.');?></div>
        </div>
      </div>
    </div>

      <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label><a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('create-password');">
            <?php _e('Create Password <span style="color:red;">*</span>:');?> </a></label>

          <input class="form-control" type="password" name="password" id="letscms_password" size="10" ><div class="toggle-visibility" id="create-password"><?php _e('Password length is atleast 6 char.', 'mlm'); ?></div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label><a style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('confirm-password');">
            <?php _e('Confirm Password <span style="color:red;">*</span>:');?> </a></label>

          <input class="form-control" type="password" name="confirm_password" id="letscms_confirm_password" size="10" ><div class="toggle-visibility" id="confirm-password"><?php _e('Please confirm your password.');?></div>
        </div>
      </div>
    </div>



      <div class="col-md-6">
        <div class="form-group">
          <button type="submit" name="submit" id="submit" class="btn btn-primary btn-default" onclick="needToConfirm = false;">Submit &raquo;</button>
        </div>
      </div>
    </div>
  </form>
  </div></div>
   <?php
  }
  else
   echo "<script>window.location='admin.php?page=binarymlm-admin-settings&tab=general&msg=s'</script>";
  } //End of register_first_user function
}
