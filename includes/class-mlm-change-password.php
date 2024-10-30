<?php
defined( 'ABSPATH' ) || exit;
class Letscms_MLM_Change_Password {
  use letscms_mlmfunction;
  public function letscms_change_password(){
    $this->letscms_check_user();
  	global $current_user;
    $error=array();
    $msg='';

  //most outer if condition
	if(isset($_POST['submit']))
	{
		$password = sanitize_text_field( $_POST['password'] );
		$confirm_pass = sanitize_text_field( $_POST['confirm_password'] );
		if ( $this->letscms_checkInputField($password) )
    $error[] = __("Please enter your new password.",'mlm');
		if ( $this->letscms_confirmPassword($password, $confirm_pass) )
    $error[] = __("Your confirm password does not match.",'mlm');
		// inner if condition
		if(empty($error))
		{
				$user = array
				(
					'ID' => $current_user->ID,
					'user_pass' => $password,
				);
				// return the wp_users table inserted user's ID
				$user_id = wp_update_user($user);
				$msg = "<div class='notibar msgsuccess'><p>Congratulations! Your password has been successfully updated.</p></div>";
		}//end inner if condition
	}//end most outer if condition
if($msg!=''){ echo $msg; }
    ?>
    <span style='color:red;'><?php if(!empty($error)){ foreach($error as $err){ echo $err."</br>"; }}?></span>
  
    	<form name="frm" method="post" action="" onSubmit="return updatePassword();">
          <div class="col-md-12">
            <div class="form-group">
              <label>New Password <span style="color:red;">*</span> :</label>
              <input type="password" class="form-control" name="password" id="letscms_password" maxlength="20" size="37" >
            <br /><span style="font-size:12px; font-style:italic; color:#666666">Password length at least 6 character</span>
            </div>
          </div>

        <div class="col-md-12">
          <div class="form-group">
            <label>Type Again<span style="color:red;">*</span> :</label>
            <input type="password" class="form-control" name="confirm_password" id="letscms_confirm_password" maxlength="20" size="37" >
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group">
            <button type="submit" name="submit" id="submit" class="btn btn-primary btn-default">Submit &raquo;</button>
          </div>
        </div>
    	</form>
    
    <?php
  }//end of function mlm_letscms_change_password()
}//end of class
